import React, { useMemo, useState, useRef, useEffect } from "react";
import { Empty, Form, Select, Spin } from "antd";
// import debounce from "lodash/debounce";

import {
  separationRules,
  // removeBaseUrl
} from "../lib/helpers";
import * as api from "../api";
const { Option } = Select;

const Text = (props) => {
  // const [value, setValue] = useState([]);

  const [loading, setLoading] = useState(true);

  const [options, setOptions] = useState([]);

  const rules = separationRules({
    pageType: props.pageType,
    rules: props.rules,
    creationRules: props.creationRules,
    updateRules: props.updateRules,
  });
  // console.log("🚀 ~ file: Select.js ~ line 68 ~ Text ~ rules", rules);

  const getOptions = (q = "") => {
    setLoading(true);
    // console.log("🚀 ~ file: Select.js ~ line 96 ~ getOptions ~ q", q);
    api.getSelect(props.dataUrl, q).then((res) => {
      // console.log("🚀 ~ file: Select.js ~ line 69 ~ .then ~ data", res);
      // // setOptions(data.data.concat(props.data));
      // let newOption = res.map((item) => ({
      //   label: item.label,
      //   value: item.value,
      // }));

      //   console.log("🚀 ~ file: Select.js ~ line 52 ~ api.getSelect ~ newOption", newOption)
      setOptions(res);
      setLoading(false);
    });
  };

  useEffect(() => {
    // console.log("🚀 ~ file: Select.js ~ line 56 ~ useEffect ~ props", props);
    if (props.dataUrl) {
      getOptions();
    }
  }, [props]);

  if (!props.dataUrl) {
    return (
      <>
        <Form.Item
          name={props.name}
          label={props.display}
          initialValue={props.value}
          rules={rules}
        >
          <Select
            mode={props.multiple ? "multiple" : false}
            options={props.data}
            allowClear
          >
          </Select>
        </Form.Item>
      </>
    );
  } else {
    return (
      <>
        <Form.Item
          name={props.name}
          label={props.display}
          initialValue={props.value}
          rules={rules}
        >
          <Select
            // onSearch={(q) => getOptions(q)}
            allowClear
            showSearch
            loading={loading}
            // notFoundContent={loading ? <Spin size="small" /> : <Empty imageStyle={{ height: 250 }} />}
            // filterOption={false}
            optionFilterProp="label"
            options={options}
            mode={props.multiple ? "multiple" : false}
          ></Select>
        </Form.Item>
      </>
    );
  }
};

export default Text;
