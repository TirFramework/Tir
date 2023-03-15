import {Col, Row, DatePicker as AntdDatePicker} from "antd";

import {useState} from "react";
import DatePicker from "./DatePicker";
import Checkbox from "./Checkbox";

const Text = (props) => {
    const [fields, setFields] = useState();
    const [data, setData] = useState({
        type: "DatePicker",
        originalName: "to",
        name: props.name,
        valueType: "string",
        value: props.value,
        display: props.display,
        placeholder: "",
        id: "to",
        class: "to",
        group: "all",
        col: 20,
        disable: false,
        readonly: fields,
        showOnIndex: true,
        showOnDetail: true,
        showOnCreating: true,
        showOnEditing: true,
        sortable: true,
        searchable: false,
        rules: "",
        creationRules: "",
        updateRules: "",
        options: props.options,
        filter: [],
        filterable: false,
        multiple: false,
        comment: [],
        additional: false,
    });

    const [dataSwitch, setDataSwitch] = useState({
        type: "DatePicker",
        originalName: "to",
        name: `${props.name}-current`,
        valueType: "string",
        value: !props.value,
        display: props.options.currentDisplay,
        placeholder: "",
        id: "to",
        class: "to",
        group: "all",
        col: 8,
        disable: false,
        readonly: fields,
        showOnIndex: true,
        showOnDetail: true,
        showOnCreating: true,
        showOnEditing: true,
        sortable: true,
        searchable: false,
        rules: "",
        creationRules: "",
        updateRules: "",
        options: [],
        filter: [],
        filterable: false,
        multiple: false,
        comment: [],
        additional: false,
    });

    return (
        <>
            <Row gutter={8}>
                <Col span={15} className="formGroup-DatePicker">
                    {!dataSwitch.value ? (
                        <DatePicker {...data} />
                    ) : (
                        <div className="ant-form-item">
                            <div className="ant-row ant-form-item-row">
                                <div className="ant-col ant-col-24 ant-form-item-label">
                                    <label title="To">To</label>
                                </div>
                                <div className="ant-col ant-col-24 ant-form-item-control">
                                    <AntdDatePicker
                                        name = {props.name}
                                        style={{width: "100%"}}
                                        defaultValue={null}
                                        value ={null}
                                        initialValue = {null}
                                    />
                                </div>
                            </div>
                        </div>
                    )}
                </Col>
                <Col span={9}>
                    <Checkbox
                        {...dataSwitch}
                        onChange={(value) => {
                            setDataSwitch({
                                ...dataSwitch,
                                value: value.target.checked,
                            });
                        }}
                    />
                </Col>
            </Row>
        </>
    );
};

export default Text;
