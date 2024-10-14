import React from "react";
import { Layout } from "antd";
import { Content } from "antd/es/layout/layout";
import AppFooter from "@/components/layout/AppFooter";

// @Components

export default function RegisterLayout({
  children,
}: {
  children: React.ReactNode;
}) {
  return (
    <>
      <Layout>
        <Layout style={{ flexDirection: "row" }}>
          <Layout>
            <Content style={{ padding: "15px" }}>
              <div
                style={{
                  background: "rgb(255, 255, 255)",
                  padding: 24,
                  minHeight: "calc(100vh - 125px)",
                  borderRadius: "8px",
                }}
              >
                {children}
              </div>
            </Content>
            <AppFooter />
          </Layout>
        </Layout>
      </Layout>
    </>
  );
}
