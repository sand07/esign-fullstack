import { LoginOutlined } from "@ant-design/icons";
import { Button, Col, Divider, Row, Space, Typography, Form, Input, message, Tabs } from "antd";
import { getProviders, signIn } from "next-auth/react";
import Image from "next/image";
import Link from "next/link";
import { useState } from "react";
import { useRouter } from "next/router";

const SignInPages = ({ providers }) => {
  const [loading, setLoading] = useState(false);
  const router = useRouter();

  const onFinishLogin = async (values) => {
    try {
      setLoading(true);
      const result = await signIn("credentials", {
        redirect: false,
        username: values.username,
        password: values.password,
      });

      if (result?.error) {
        message.error("Login gagal: " + result.error);
      } else if (result?.ok) {
        message.success("Login berhasil!");
        router.push("/");
      }
    } catch (error) {
      message.error("Terjadi kesalahan saat login");
      console.error(error);
    } finally {
      setLoading(false);
    }
  };

  const onFinishRegister = async (values) => {
    try {
      setLoading(true);
      const response = await fetch("/api/auth/register", {
        method: "POST",
        headers: {
          "Content-Type": "application/json",
        },
        body: JSON.stringify(values),
      });

      const data = await response.json();

      if (response.ok) {
        message.success("Registrasi berhasil! Silakan login.");
        // Auto login setelah register
        await signIn("credentials", {
          redirect: false,
          username: values.username,
          password: values.password,
        });
        router.push("/");
      } else {
        message.error(data.message || "Registrasi gagal");
      }
    } catch (error) {
      message.error("Terjadi kesalahan saat registrasi");
      console.error(error);
    } finally {
      setLoading(false);
    }
  };

  // Filter out credentials provider from oauth providers
  const oauthProviders = Object.values(providers || {}).filter(
    (provider) => provider.id !== "credentials"
  );

  return (
    <Row style={{ minHeight: "100vh" }} align="middle" justify="center">
      <Col xs={22} sm={20} md={16} lg={12} xl={10}>
        <Typography.Title>E-Sign BKD</Typography.Title>

        <Tabs defaultActiveKey="local">
          <Tabs.TabPane tab="Login Lokal" key="local">
            <Form
              name="login"
              onFinish={onFinishLogin}
              layout="vertical"
              autoComplete="off"
            >
              <Form.Item
                label="Username atau Email"
                name="username"
                rules={[
                  { required: true, message: "Mohon isi username atau email!" },
                ]}
              >
                <Input placeholder="Masukkan username atau email" />
              </Form.Item>

              <Form.Item
                label="Password"
                name="password"
                rules={[{ required: true, message: "Mohon isi password!" }]}
              >
                <Input.Password placeholder="Masukkan password" />
              </Form.Item>

              <Form.Item>
                <Button
                  type="primary"
                  htmlType="submit"
                  loading={loading}
                  icon={<LoginOutlined />}
                  block
                >
                  Login
                </Button>
              </Form.Item>
            </Form>
          </Tabs.TabPane>

          <Tabs.TabPane tab="Registrasi" key="register">
            <Form
              name="register"
              onFinish={onFinishRegister}
              layout="vertical"
              autoComplete="off"
            >
              <Form.Item
                label="Username"
                name="username"
                rules={[
                  { required: true, message: "Mohon isi username!" },
                  { min: 3, message: "Username minimal 3 karakter" },
                ]}
              >
                <Input placeholder="Masukkan username" />
              </Form.Item>

              <Form.Item
                label="Email"
                name="email"
                rules={[
                  { required: true, message: "Mohon isi email!" },
                  { type: "email", message: "Format email tidak valid!" },
                ]}
              >
                <Input placeholder="Masukkan email" />
              </Form.Item>

              <Form.Item
                label="Password"
                name="password"
                rules={[
                  { required: true, message: "Mohon isi password!" },
                  { min: 6, message: "Password minimal 6 karakter" },
                ]}
              >
                <Input.Password placeholder="Masukkan password" />
              </Form.Item>

              <Form.Item
                label="Nama Lengkap"
                name="name"
              >
                <Input placeholder="Masukkan nama lengkap (opsional)" />
              </Form.Item>

              <Form.Item
                label="NIK"
                name="nik"
              >
                <Input placeholder="Masukkan NIK (opsional)" />
              </Form.Item>

              <Form.Item
                label="NIP"
                name="employee_number"
              >
                <Input placeholder="Masukkan NIP (opsional)" />
              </Form.Item>

              <Form.Item>
                <Button
                  type="primary"
                  htmlType="submit"
                  loading={loading}
                  block
                >
                  Daftar
                </Button>
              </Form.Item>
            </Form>
          </Tabs.TabPane>

          {oauthProviders.length > 0 && (
            <Tabs.TabPane tab="Login SSO" key="sso">
              <Space direction="vertical" style={{ width: "100%" }}>
                {oauthProviders.map((provider) => (
                  <Button
                    key={provider.name}
                    icon={<LoginOutlined />}
                    type="primary"
                    onClick={() => signIn(provider.id)}
                    block
                  >
                    Login with {provider.name}
                  </Button>
                ))}
              </Space>
            </Tabs.TabPane>
          )}
        </Tabs>
        <Divider />
        <div
          style={{
            marginBottom: 10,
          }}
        >
          <Space size="small">
            <Image
              alt="pemprov"
              src="https://siasn.bkd.jatimprov.go.id:9000/public/pemprov.png"
              width={15}
              height={20}
            />
            <Image
              alt="logobkd"
              src="https://siasn.bkd.jatimprov.go.id:9000/public/logobkd.jpg"
              width={30}
              height={40}
            />
            <Image
              alt="logobsre"
              src="https://siasn.bkd.jatimprov.go.id:9000/public/logobsre.png"
              width={50}
              height={20}
            />
          </Space>
        </div>
        <Space direction="vertical" size="small">
          <Typography.Text>
            <Link href="/check">Check Document?</Link>
          </Typography.Text>
          <span>&#169; 2023 BKD Provinsi Jawa Timur</span>
          <Link
            href="https://github.com/taufiqurrohmansuwarto/esign-fullstack"
            passHref
            target="_blank"
          >
            This code available on github
          </Link>
          <Typography.Text>Version 1.0.alpha.4 - 08-02-2023</Typography.Text>
        </Space>
      </Col>
      <Col span={6}>
        <Image
          alt="Mountains"
          src="https://siasn.bkd.jatimprov.go.id:9000/public/desktop.png"
          width={550}
          height={400}
        />
      </Col>
    </Row>
  );
};

export async function getServerSideProps() {
  const providers = await getProviders();

  return {
    props: {
      providers,
    },
  };
}

export default SignInPages;
