import React, {useEffect, useState} from 'react';
import {UploadOutlined} from '@ant-design/icons';
import {Button, Divider, message, Tooltip, Upload} from 'antd';
import moment from "moment"
import Cookies from "js-cookie";
import axios from "axios";
import Config from "../constants/config";


const options = {
    maxCount: 1,
};

const token = Cookies.get("api_token");

const App = (props) => {

    const [fileList, setFileList] = useState([]);

    useEffect(() => {
        if (props.hasOwnProperty('value')) {
            let newFileList = [
                {
                    uid: '-1',
                    name: props.display,
                    status: 'done',
                    // url: props.downloadUrl + '/' + props.value.id + '/' + props.value.name,
                    download: props.downloadUrl + '/' + props.value.id + '/' + props.value.name,
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
                file.name = props.display,
                file.download = props.downloadUrl + '/' + file.response.id + '/' + file.response.name;

            }
            return file;
        });
        setFileList(newFileList);
    };


    const handleRemove = (file) => {
        setFileList([]);
    }


    const handleDownload = (file) => {
        // window.open(file.download);

        axios.defaults.baseURL = Config.apiBaseUrl;
        const token = Cookies.get("api_token");
        axios({
            url: file.download, //your url
            method: 'GET',
            responseType: 'blob', // important
            headers: {
                'Authorization': `Bearer ${token}`
            }
        }).then((response) => {
            // create file link in browser's memory
            const href = URL.createObjectURL(response.data);

            // create "a" HTML element with href to file & click
            const link = document.createElement('a');
            link.href = href;
            link.setAttribute('download', response.headers['content-disposition'].split('filename=')[1]); //or any other extension
            document.body.appendChild(link);
            link.click();

            // clean up "a" element & remove ObjectURL
            document.body.removeChild(link);
            URL.revokeObjectURL(href);
        });

    }
    return (
        <>
            <div className={"documentUploader"}>
                <Tooltip placement="topLeft" title={props.options.tooltip} content>
                    <div className="label">{props.display}</div>
                </Tooltip>

                <Upload {...options}
                        name={props.name}
                        data={{inputName: props.name}}
                        headers={{Authorization: `Bearer ${token}`}}
                        action={props.uploadUrl}
                        beforeUpload={beforeUpload}
                        onDownload = {handleDownload}
                        onChange={handleChange}
                        onRemove={handleRemove}
                        listType="docs"
                        fileList={fileList}
                        showUploadList={{
                            showDownloadIcon: true,
                            showRemoveIcon: true,
                        }}

                >
                    {!fileList.length > 0 && <Button icon={<UploadOutlined/>}></Button>}
                </Upload>
                {fileList.length > 0 && <div className="upload-username">{props?.value?.user_name}</div>}
                {fileList.length > 0 &&
                    <div className="upload-date">{moment.utc(props?.value?.uploaded_at).format('YYYY-MM-DD')}</div>}
            </div>
        </>
    )
        ;
}
export default App;

