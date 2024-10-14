import { Suspense } from "react";
import { Layout } from "antd";
import { Content } from "antd/es/layout/layout";
import AppFooter from "@/components/layout/AppFooter";

export default function Home() {
  return (
    <Suspense>
      <Layout className="h-svh">
        <Layout style={{ flexDirection: "row" }}>
          <Layout>
            <Content
              style={{
                padding: "15px",
                display: "flex",
                justifyContent: "center",
                alignItems: "center",
                background: "rgb(255, 255, 255)",
              }}
            >
              <div
                className="max-w-md"
                style={{
                  padding: 24,
                  borderRadius: "8px",
                }}
              >
                <div className="p-4">
                  Demo APP
                </div>
              </div>
            </Content>
            <AppFooter
              style={{
                padding: "15px 10px 10px 10px",
                backgroundColor: "#fff",
              }}
            />
          </Layout>
        </Layout>
      </Layout>
    </Suspense>
  );
}
