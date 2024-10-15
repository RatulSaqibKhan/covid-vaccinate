"use client";

import React from "react";
import Link from "next/link";

import { Layout, Menu, MenuProps } from "antd";
import { usePathname } from "next/navigation";

const { Header } = Layout;

function TopNavbar() {
  // Get the current route path using Next.js's usePathname hook
  const pathname = usePathname();
  const menu: MenuProps["items"] = [
    {
      key: "home",
      label: <Link href="/">Home</Link>,
    },
    {
      key: "register",
      label: <Link href="/register">Register</Link>,
    },
  ];

  // Set the selected key based on the current path
  const selectedKey = pathname.startsWith("/register") ? "register" : "home";
  return (
    <Header
      style={{
        display: "flex",
        alignItems: "flex-end",
        justifyContent: "space-between",
      }}
    >
      <Menu
        theme="dark"
        mode="horizontal"
        items={menu}
        selectedKeys={[selectedKey]}
        style={{ flex: 1, minWidth: 0 }}
      />
    </Header>
  );
}

export default TopNavbar;
