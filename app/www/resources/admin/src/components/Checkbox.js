import { Form, Checkbox } from "antd";
import { separationRules } from "../lib/helpers";
import { CheckOutlined, CloseOutlined } from "@ant-design/icons";

const Text = (props) => {
  const rules = separationRules({
    pageType: props.pageType,
    rules: props.rules,
    creationRules: props.creationRules,
    updateRules: props.updateRules,
  });

if (props.readonly) {
    return (
      <>
        {props.hideLable ?? <div>{props.display}</div>}
        {props.value ? <CheckOutlined style={{ color: 'green' }} /> : <CloseOutlined style={{ color: 'red' }} />}
      </>
    );
  }

  return (
    <>
      <Form.Item
        // label={props.display}
        name={props.name}
        initialValue={props.value}
        valuePropName="checked"
        rules={rules}
        labelCol={{
          flex: "none",
        }}
        wrapperCol={{
          flex: "auto",
        }}
      >
        <Checkbox
          onChange={props.onChange}
          disabled={props.readonly}
          className={props.readonly && "readOnly"}
        >{props.display}</Checkbox>
      </Form.Item>
    </>
  );
};


export default Text;
