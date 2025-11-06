import { upsertUserAttr } from "lib/auth-utils";
import NextAuth from "next-auth/next";
import CredentialsProvider from "next-auth/providers/credentials";
import bcrypt from "bcryptjs";
import prisma from "lib/prisma";

const masterClientId = process.env.MASTER_ID;
const masterClientSecret = process.env.MASTER_SECRET;
const masterWellKnown = process.env.MASTER_WELLKNOWN;
const masterScope = process.env.MASTER_SCOPE;

// PTTPK
const pttpkClientId = process.env.PTTPK_ID;
const pttpkClientSecret = process.env.PTTPK_SECRET;
const pttpkWellKnown = process.env.PTTPK_WELLKNOWN;
const pttpkScope = process.env.PTTPK_SCOPE;

export default async function (req, res) {
  const ip = req?.headers["x-forwarded-for"] || req?.connection?.remoteAddress;
  const useragent = req?.headers["user-agent"];

  return NextAuth(req, res, {
    pages: {
      signIn: "/esign/signin",
    },
    callbacks: {
      redirect: async (url, baseUrl) => {
        const urlCallback = `${url?.baseUrl}${process.env.BASE_PATH}`;
        return urlCallback;
      },
      async session({ session, token, user }) {
        session.accessToken = token.accessToken;
        session.expires = token?.expires;

        // session.scope = token.scope;
        session.user.id = token.id;
        session.user.role = token?.role;
        session.user.group = token?.group;
        session.user.employee_number = token?.employee_number;
        session.user.organization_id = token?.organization_id;
        session.user.name = token?.username;
        session.user.nik = token?.nik;
        session.user.useragent = token?.useragent;
        session.user.current_role = token?.role;

        const check = Date.now() < new Date(token?.expires * 1000);

        if (check) {
          return session;
        }
      },
      async jwt({ token, user, account, profile, isNewUser }) {
        if (account) {
          // Handle Credentials Provider
          if (account.type === "credentials") {
            token.id = user.id;
            token.username = user.username;
            token.role = user.role;
            token.group = user.group;
            token.employee_number = user.employee_number;
            token.organization_id = user.organization_id;
            token.current_role = user.role;
            token.nik = user.nik;
          } else {
            // Handle OAuth Provider
            token.accessToken = account?.access_token;
            token.expires = profile?.exp;
            token.useragent = useragent;
            token.username = profile?.name;
            token.id = account?.providerAccountId;
            token.role = profile?.role;
            token.group = profile?.group;
            token.employee_number = profile?.employee_number;
            token.organization_id = profile?.organization_id;
            token.current_role = user?.current_role;
            token.nik = user?.nik;
          }
        }
        return token;
      },
    },
    secret: process.env.NEXAUTH_SECRET,
    theme: {
      brandColor: "white",
      colorScheme: "light",
      buttonText: "white",
    },
    jwt: {
      secret: process.env.NEXTAUTH_SECRET,
    },
    providers: [
      CredentialsProvider({
        id: "credentials",
        name: "Credentials",
        credentials: {
          username: { label: "Username", type: "text" },
          password: { label: "Password", type: "password" }
        },
        async authorize(credentials, req) {
          try {
            const { username, password } = credentials;

            // Cari user berdasarkan username atau email
            const user = await prisma.User.findFirst({
              where: {
                OR: [
                  { username: username },
                  { email: username }
                ]
              }
            });

            if (!user) {
              throw new Error("Username atau password salah");
            }

            // Cek apakah user memiliki password (untuk local auth)
            if (!user.password) {
              throw new Error("Akun ini tidak mendukung login lokal");
            }

            // Verifikasi password
            const isValid = await bcrypt.compare(password, user.password);

            if (!isValid) {
              throw new Error("Username atau password salah");
            }

            // Update last login
            await prisma.User.update({
              where: { id: user.id },
              data: {
                last_login: new Date(),
                is_online: true
              }
            });

            // Log login history
            const ip = req?.headers?.["x-forwarded-for"] || req?.connection?.remoteAddress;
            const useragent = req?.headers?.["user-agent"];

            await prisma.History.create({
              data: {
                user_id: user.id,
                ip_address: ip,
                action: "LOGIN",
                type: "ACCOUNT",
                activity: "Local Login",
                created_at: new Date(),
                useragent,
              },
            });

            return {
              id: user.id,
              email: user.email,
              username: user.username,
              group: user.group || "LOCAL",
              role: user.role || "USER",
              employee_number: user.employee_number,
              organization_id: user.organization_id,
              image: user.image,
              nik: user.nik,
            };
          } catch (error) {
            console.error("Login error:", error);
            return null;
          }
        }
      }),
      {
        name: "SIMASTER",
        id: "esign",
        type: "oauth",
        wellKnown: masterWellKnown,
        clientId: masterClientId,
        clientSecret: masterClientSecret,
        authorization: {
          params: {
            scope: masterScope,
            prompt: "login",
          },
        },
        idToken: true,
        checks: ["pkce", "state"],
        profile: async (profile, tokens) => {
          try {
            const accessToken = tokens?.access_token;
            const currentUser = {
              id: profile?.sub,
              email: profile?.email,
              nik: profile?.nik,
              username: profile?.name,
              group: profile?.group,
              role: profile?.role,
              organization_id: profile?.organization_id,
              image: profile?.picture,
              employee_number: profile?.employee_number,
            };

            await upsertUserAttr(
              currentUser.id,
              currentUser,
              accessToken,
              ip,
              useragent
            );

            return currentUser;
          } catch (error) {
            console.log(error);
          }
        },
      },
      {
        name: "PTT-PK",
        id: "pttpk",
        type: "oauth",
        wellKnown: pttpkWellKnown,
        clientId: pttpkClientId,
        clientSecret: pttpkClientSecret,
        authorization: {
          params: {
            scope: pttpkScope,
            prompt: "login",
          },
        },
        idToken: true,
        checks: ["pkce", "state"],
        profile: async (profile, tokens) => {
          try {
            const accessToken = tokens?.access_token;
            const currentUser = {
              id: profile?.sub,
              email: profile?.email,
              nik: profile?.nik,
              username: profile?.name,
              group: profile?.group,
              role: profile?.role,
              organization_id: profile?.organization_id,
              image: profile?.picture,
              employee_number: profile?.employee_number,
            };

            await upsertUserAttr(
              currentUser.id,
              currentUser,
              accessToken,
              ip,
              useragent
            );
            return currentUser;
          } catch (error) {
            console.log(error);
          }
        },
      },
    ],
  });
}
