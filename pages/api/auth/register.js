import bcrypt from "bcryptjs";
import prisma from "lib/prisma";
import { nanoid } from "nanoid";

export default async function handler(req, res) {
  if (req.method !== "POST") {
    return res.status(405).json({ message: "Method not allowed" });
  }

  try {
    const { username, password, email, name, nik, employee_number } = req.body;

    // Validasi input
    if (!username || !password) {
      return res.status(400).json({
        message: "Username dan password wajib diisi"
      });
    }

    if (password.length < 6) {
      return res.status(400).json({
        message: "Password minimal 6 karakter"
      });
    }

    // Cek apakah username sudah ada
    const existingUser = await prisma.User.findFirst({
      where: {
        OR: [
          { username: username },
          { email: email }
        ]
      }
    });

    if (existingUser) {
      return res.status(400).json({
        message: "Username atau email sudah terdaftar"
      });
    }

    // Hash password
    const hashedPassword = await bcrypt.hash(password, 10);

    // Buat user baru
    const newUser = await prisma.User.create({
      data: {
        id: nanoid(),
        username: username,
        password: hashedPassword,
        email: email || null,
        username: name || username,
        nik: nik || null,
        employee_number: employee_number || null,
        group: "LOCAL",
        role: "USER",
        is_online: false,
        createdat: new Date(),
      }
    });

    // Jangan kirim password ke client
    const { password: _, ...userWithoutPassword } = newUser;

    return res.status(201).json({
      success: true,
      message: "Registrasi berhasil",
      data: userWithoutPassword
    });

  } catch (error) {
    console.error("Registration error:", error);
    return res.status(500).json({
      message: "Terjadi kesalahan saat registrasi",
      error: error.message
    });
  }
}
