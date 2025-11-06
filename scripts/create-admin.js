// Script untuk membuat admin user
// Usage: node scripts/create-admin.js

const bcrypt = require("bcryptjs");
const { PrismaClient } = require("@prisma/client");
const { nanoid } = require("nanoid");

const prisma = new PrismaClient();

async function createAdmin() {
  try {
    const username = process.env.ADMIN_USERNAME || "admin";
    const password = process.env.ADMIN_PASSWORD || "admin123";
    const email = process.env.ADMIN_EMAIL || "admin@esign.local";

    // Cek apakah admin sudah ada
    const existingAdmin = await prisma.User.findFirst({
      where: {
        OR: [{ username: username }, { email: email }],
      },
    });

    if (existingAdmin) {
      console.log("❌ Admin sudah ada!");
      console.log("Username:", existingAdmin.username);
      console.log("Email:", existingAdmin.email);
      process.exit(1);
    }

    // Hash password
    const hashedPassword = await bcrypt.hash(password, 10);

    // Buat admin user
    const admin = await prisma.User.create({
      data: {
        id: nanoid(),
        username: username,
        password: hashedPassword,
        email: email,
        group: "LOCAL",
        role: "ADMIN",
        is_online: false,
        createdat: new Date(),
      },
    });

    console.log("✅ Admin berhasil dibuat!");
    console.log("Username:", admin.username);
    console.log("Email:", admin.email);
    console.log("Password:", password);
    console.log("\n⚠️  PENTING: Segera ganti password setelah login pertama!");
  } catch (error) {
    console.error("❌ Error membuat admin:", error.message);
    process.exit(1);
  } finally {
    await prisma.$disconnect();
  }
}

createAdmin();
