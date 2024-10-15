"use client";
import { BackendServiceApiResponseInterface } from "@/libs/api-response-interfaces/BackendServiceApiResponseInterface";
import CommonResponse from "@/libs/api-response-interfaces/CommonResponse";
import { User } from "@/libs/models/User";
import { createBackendService } from "@/libs/services/BackendApiService";
import { Button, Col, Divider, Input, notification, Row, Table } from "antd";
import React, { useCallback, useEffect, useState } from "react";
import type { GetProps, TableProps } from "antd";
import { useRouter } from "next/navigation";

type SearchProps = GetProps<typeof Input.Search>;

const { Search } = Input;

interface DataType {
  status: string;
  scheduled_date?: string;
}

const SearchForm: React.FC = () => {
  const [loading, setLoading] = useState<boolean>(false);
  const [user, setUser] = useState<User | null>(null);
  const [columns, setColumns] = useState<TableProps<DataType>["columns"]>([]);
  const [data, setData] = useState<DataType[]>([]);
  const router = useRouter();

  const gotoResisterPage = useCallback(() => {
    router.push("/register");
  }, [router]);

  useEffect(() => {
    let columns: TableProps<DataType>["columns"] = [];
    let data: DataType[] = [];

    if (user) {
      const status = user.status as string;
      const scheduledDate = user.scheduled_date as string;
      if (["Vaccinated", "Not Scheduled"].includes(status)) {
        columns = [
          {
            title: "Status",
            dataIndex: "status",
            key: "status",
            render: (text) => text,
          },
        ];
        data = [
          {
            status,
          },
        ];
      } else if (status === "Scheduled") {
        columns = [
          {
            title: "Status",
            dataIndex: "status",
            key: "status",
            render: (text) => text,
          },
          {
            title: "Scheduled Date",
            dataIndex: "scheduled_date",
            key: "scheduled_date",
            render: (text) => text,
          },
        ];
        data = [
          {
            status,
            scheduled_date: scheduledDate,
          },
        ];
      } else {
        columns = [
          {
            title: "Status",
            dataIndex: "status",
            key: "status",
            render: (text) => text,
          },
          {
            title: "Action",
            key: "action",
            render: () => (
              <Button color="primary" onClick={gotoResisterPage}>
                Register
              </Button>
            ),
          },
        ];

        data = [
          {
            status: "Not registered",
          },
        ];
      }
    }
    setColumns(columns);
    setData(data);
  }, [gotoResisterPage, user]);

  const onSearch: SearchProps["onSearch"] = async (value) => {
    setColumns([]);
    setData([]);
    if (!value || value === "") {
      notification.warning({
        message: "Please write your NID",
      });
      return;
    }

    if (isNaN(Number(value))) {
      notification.warning({
        message: "Please write your valid NID",
      });
      return;
    }

    setLoading(true);
    setUser(null);
    const nid: string = value;

    try {
      const response: CommonResponse<
        BackendServiceApiResponseInterface<User | null>
      > = await createBackendService().userSearch(nid);

      let userData: User | null = response.data.data ?? null;
      if (!userData) {
        userData = {
          name: "",
          email: "",
          nid: `${nid}`,
          phone: "",
          vaccine_center_id: "",
          status: "Not Registered",
        };
      }
      setUser(userData);
      console.log(user);
    } catch (error) {
      const message = error?.error?.response?.data?.message;
      const reason = error?.error?.response?.data?.reason;
      notification.error({
        message: message || "Something went wrong",
        description: reason,
      });
    } finally {
      setLoading(false);
    }
  };

  return (
    <>
      <Row gutter={24}>
        <Col span={24}>
          <Search
            placeholder="Enter your NID"
            enterButton
            size="large"
            onSearch={onSearch}
            loading={loading}
          />
        </Col>
      </Row>
      {data && data.length > 0 ? (
        <>
          <Divider />
          <Row gutter={24}>
            <Col span={24}>
              <Table<DataType>
                columns={columns}
                dataSource={data}
                pagination={false}
              />
            </Col>
          </Row>
        </>
      ) : (
        ""
      )}
    </>
  );
};

export default SearchForm;
