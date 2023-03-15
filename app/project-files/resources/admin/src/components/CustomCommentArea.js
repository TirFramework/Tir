import {Avatar, Button, Drawer, Comment, Form, Input, List, Select, Tag, Tooltip} from 'antd';
import axios from "../lib/axios";
import {useEffect, useState} from "react";
import moment from "moment"

import {CommentOutlined, UserOutlined} from '@ant-design/icons';
import Config from "../constants/config";

const {TextArea} = Input;
const CommentList = ({comments}) => (
    <List
        dataSource={comments}
        header={`${comments.length} ${comments.length > 1 ? 'replies' : 'reply'}`}
        itemLayout="horizontal"
        renderItem={(props) => <Comment {...props} />}
    />
);

const Editor = ({onChange, onSubmit, submitting, value}) => (
    <>
        <Form.Item>
            <TextArea rows={4} onChange={onChange} value={value}/>
        </Form.Item>
        <Form.Item>
            <Button htmlType="submit" loading={submitting} onClick={onSubmit} type="primary">
                Add Comment
            </Button>
        </Form.Item>
    </>
);


const CommentArea = (props) => {

    const [open, setOpen] = useState(false);

    const showDrawer = () => {
        setOpen(true);
    };

    const onClose = () => {
        setOpen(false);
    };

    //Comments Functions

    const [comments, setComments] = useState([]);
    const [submitting, setSubmitting] = useState(false);
    const [value, setValue] = useState('');
    const [mentions, setMentions] = useState([]);


    const handleMentionsChange = (mention) => {
        setMentions(mention);
    };

    let data = props.value;
    if(data === undefined) {
        data = [];
    }
    useEffect(() => {
        data.map((item) => {
            let mentions = item.mentions;
            mentions = mentions.map((m, index) =>
                (
                    <Tooltip title={m} placement="top" key={index}>
                        <Tag color="cyan"> @{m}</Tag>
                    </Tooltip>
                )
            )
            item.content = (<><Avatar.Group> {mentions} </Avatar.Group> {item.content}</>);
            item.datetime = moment.utc(item.updated_at).fromNow();
            item.avatar = <UserOutlined />;
        })
    }, [])

    useEffect(() => {
        setComments(data)
    }, [])

    const handleSubmit = async () => {
        if (!value) return;
        setSubmitting(true);
        const body = {
            content: value,
            module: props.module,
            record_id: props.record_id,
            mentions: mentions
        }

        axios.post('http://crm:8001/api/v1/admin/comment', body).then((res)=>{

            const responseData = res.data.item;
            let mentions = responseData.mentions;
            mentions = mentions.map((m, index) =>
                (
                    <Tooltip title={m} placement="top" key={index}>
                        <Tag color="cyan"> @{m}</Tag>
                    </Tooltip>
                )
            )
            const newComment = {
                author: responseData.author,
                content: (<><Avatar.Group> {mentions} </Avatar.Group> {responseData.content}</>),
                avatar:  <UserOutlined />,
                datetime: moment(responseData.content.updated_at).fromNow()
            }
            setSubmitting(false);
            setComments([
                ...comments,
                newComment
            ]);
            setValue('')
        });
    };
    const handleChange = (e) => {
        setValue(e.target.value);
    };


    return (
        <>
            <Button type="primary" onClick={showDrawer}> <CommentOutlined /> </Button>
            <Drawer
                className={'comment-drawer'}
                title="Comments"
                placement='right'
                closable={true}
                onClose={onClose}
                open={open}
                key='right'
                size='large'
            >
                {comments.length > 0 && <CommentList comments={comments}/>}
                <Comment
                    avatar={ <UserOutlined />}
                    content={
                        <>
                            <Form.Item>
                                <Select
                                    name={'mention'}
                                    mode="multiple"
                                    onChange={handleMentionsChange}
                                    value={mentions}
                                    options={props.users}
                                />
                            </Form.Item>
                            <Editor
                                onChange={handleChange}
                                onSubmit={handleSubmit}
                                submitting={submitting}
                                value={value}
                            />
                        </>

                    }
                />
            </Drawer>
        </>
    );
}
export default CommentArea;

