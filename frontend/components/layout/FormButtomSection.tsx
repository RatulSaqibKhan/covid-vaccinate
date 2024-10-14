import { Button, Flex, Form, Space } from "antd";
import { FormInstance } from "antd/lib";
const tailLayout = {
  wrapperCol: { offset: 20, span: 4 },
};

export interface FormButtonSectionProps {
  loading: boolean;
  form: FormInstance;
  resetBtnHide?: boolean;
}

const FormButtonSection: React.FC<FormButtonSectionProps> = (
  props: FormButtonSectionProps
) => {
  const { loading, form, resetBtnHide } = props;
  const resetBtnNeeded = resetBtnHide !== undefined ? !resetBtnHide : true;

  const onReset = () => {
    form.resetFields();
  };
  return (
    <Form.Item {...tailLayout}>
      <Flex justify="flex-end" align="flex-start">
        <Space>
          <Button type="primary" htmlType="submit" loading={loading}>
            Submit
          </Button>
          {resetBtnNeeded ? (
            <Button type="default" htmlType="button" onClick={onReset}>
              Reset
            </Button>
          ) : (
            ""
          )}
        </Space>
      </Flex>
    </Form.Item>
  );
};

export default FormButtonSection;
