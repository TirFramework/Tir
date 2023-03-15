import React, {useEffect, useState} from 'react';
import {UploadOutlined} from '@ant-design/icons';
import {Button, Divider, Form, message, Tooltip, Upload} from 'antd';
import moment from "moment"
import Cookies from "js-cookie";
import {separationRules} from "../lib/helpers";


const options = {
    maxCount: 1,
};

const token = Cookies.get("api_token");

const App = (props) => {

    const [fileList, setFileList] = useState([]);
    const [inputValue, setInputValue] = useState([]);

    const rules = separationRules({
        pageType: props.pageType,
        rules: props.rules,
        creationRules: props.creationRules,
        updateRules: props.updateRules,
    });


    useEffect(() => {
        if (props.hasOwnProperty('value')) {
            let newFileList = [
                {
                    uid: '-1',
                    name: props.display,
                    status: 'done',
                    url: props.downloadUrl + '/' + props.value.id + '/' + props.value.name,
                }
            ]
            setFileList(newFileList);
        }
    }, [])


    const beforeUpload = (file) => {

        const isPdf = file.type === 'application/pdf';
        if (!isPdf) {
            message.error('You can only upload Pdf file!');
        }

        const isLt2M = file.size / 1024 / 1024 < 2;
        if (!isLt2M) {
            message.error('Image must smaller than 2MB!');
        }
        return isPdf && isLt2M;
    };

    const handleChange = (info) => {
        let newFileList = [...info.fileList];

        // 1. Limit the number of uploaded files
        // Only to show two recent uploaded files, and old ones will be replaced by the new
        newFileList = newFileList.slice(-2);

        // 2. Read from response and show file link
        newFileList = newFileList.map((file) => {
            if (file.response) {
                // Component will show file.url as link
                file.url = props.downloadUrl + '/' + file.response.id + '/' + file.response.name;
                setInputValue([{
                    id:file.response.id,
                    title:file.response.name
                }]);
            }
            return file;
        });
        setFileList(newFileList);

    };


    const handleRemove = (file) => {
        setFileList([]);
    }


    return (
        <>
            <div className={"documentUploader"}>
                <Tooltip placement="topLeft" title={props.display}>
                    <div className="label">{props.display}</div>
                </Tooltip>

                <Upload {...options}
                        name={props.name}
                        data={{inputName: props.name}}
                        headers={{ Authorization: `Bearer ${token}` }}
                        action={props.uploadUrl}
                        beforeUpload={beforeUpload}
                        onChange={handleChange}
                        onRemove={handleRemove}
                        listType="docs"
                        fileList={fileList}
                        showUploadList={{
                            showRemoveIcon: true,
                        }}

                >
                    {!fileList.length > 0 && <Button icon={<UploadOutlined/>}></Button> }
                </Upload>
            </div>

        </>
    )
        ;
}
export default App;

