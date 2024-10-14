import React from "react";
import { Footer } from "antd/es/layout/layout";

interface AppFooterProps {
  style?: React.CSSProperties | undefined;
}
const AppFooter: React.FC<AppFooterProps> = ({ style }: AppFooterProps) => {
  let defaultStyle: React.CSSProperties = {
    textAlign: "center",
    padding: "0 10px 10px 10px",
  };
  if (style) {
    defaultStyle = { ...defaultStyle, ...style };
  }
  return (
    <Footer style={defaultStyle}>
      Covid Vaccinate Registration System ©{new Date().getFullYear()}
    </Footer>
  );
};

export default AppFooter;
