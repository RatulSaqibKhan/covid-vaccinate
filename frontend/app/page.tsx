import { Layout } from "antd";
import { Content } from "antd/es/layout/layout";
import AppFooter from "@/components/layout/AppFooter";
import SearchFormContainer from "@/components/containers/SearchFormContainer";
import TopNavbar from "@/components/layout/TopNavbar";

export default function Home() {
  return (
    <Layout>
        <TopNavbar/>
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
              <SearchFormContainer />
            </div>
          </Content>
          <AppFooter />
        </Layout>
      </Layout>
    </Layout>
  );
}
