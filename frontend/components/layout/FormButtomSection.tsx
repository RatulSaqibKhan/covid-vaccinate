import { Button, Form, Space } from "antd";
import { FormInstance } from "antd/lib";
const tailLayout = {
  wrapperCol: { span: 24 },
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
    </Form.Item>
  );
};

export default FormButtonSection;
