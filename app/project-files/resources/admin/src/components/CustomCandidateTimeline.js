import {CheckCircleOutlined, ClockCircleOutlined, CloseCircleOutlined} from '@ant-design/icons';
import {Button, Col, Row, Tag, Timeline} from 'antd';

const CustomCandidateTimeline = (props) => {

    return (
        <>
            <Row>
                <Col span={8}>

                    <Timeline mode="left">
                        <Timeline.Item dot={<Button type="primary" shape="round">Job Contract</Button>}> <br/> <br/>
                        </Timeline.Item>
                        <Timeline.Item label="Potential Employers">
                            <small>Employer 1</small>
                            <br />
                            <small>Employer 2</small>
                        </Timeline.Item>

                        <Timeline.Item label="Interviews">
                            <small> 2023-10-12</small>
                            <br />
                            <small> 2023-10-18</small>
                        </Timeline.Item>

                        <Timeline.Item label="Contract">
                            <small> Employer 1</small>
                            <br />
                            <small>Start Date: 2023-10-18 </small>
                        </Timeline.Item>

                        <Timeline.Item label="State">
                            <small> Bayern </small>
                        </Timeline.Item>
                    </Timeline>
                </Col>

                <Col span={8}>
                    <Timeline mode="left">
                        <Timeline.Item dot={<Button type="primary" shape="round">Document</Button>}> <br/> <br/>
                        </Timeline.Item>
                        <Timeline.Item label="Original docs">
                            <Tag color={"green"}>Approved</Tag>
                            <br/>
                            <small>Received: 2023-02-14</small>
                        </Timeline.Item>
                        <Timeline.Item color="green" label="Translation" dot={<CheckCircleOutlined/>}>
                            <Tag color={"green"}>Approved</Tag>
                            <br/>
                            <small>Sent: 2023-02-14</small>
                            <br/>
                            <small>Due: 2023-02-14</small>
                            <br/>
                            <small>Received: 2023-02-14</small>
                        </Timeline.Item>
                        <Timeline.Item color="red" dot={<CloseCircleOutlined/>} label="Legalization"> <Tag
                            color={"red"}>Rejected</Tag> <br/> <small>Received: 2023-02-14</small> </Timeline.Item>
                        <Timeline.Item color="blue" dot={<ClockCircleOutlined/>} label="Extra Document">
                            <Tag color={"blue"}>In Progress</Tag>
                            <br/>
                            <small>Received: 2023-02-14</small>
                        </Timeline.Item>
                        <Timeline.Item color="blue" label="Final Check"  dot={<ClockCircleOutlined/>}>
                            <Tag color={"blue"}>In Progress</Tag>
                            <br/>
                            <small>Received: 2023-02-14</small>
                        </Timeline.Item>
                        <Timeline.Item color="gray" label="Posting"> - </Timeline.Item>
                        <Timeline.Item color="gray" label="Confirmation by translator"> - </Timeline.Item>
                        <Timeline.Item color="gray" label="Job Contract"> - </Timeline.Item>
                        <Timeline.Item color="gray" label="Recognition"> - </Timeline.Item>
                        <Timeline.Item color="gray" label="EZB"> - </Timeline.Item>
                        <Timeline.Item color="gray" label="ZAV"> - </Timeline.Item>
                        <Timeline.Item color="gray" label="German Certificate"> - </Timeline.Item>
                        <Timeline.Item color="gray" label="Medical Insurance"> - </Timeline.Item>
                        <Timeline.Item color="gray" label="Vaccination"> - </Timeline.Item>
                        <Timeline.Item color="gray" label="Visa"> - </Timeline.Item>
                    </Timeline>
                </Col>

                <Col span={8}>
                    <Timeline mode="left">
                        <Timeline.Item dot={<Button type="primary" shape="round">German Language</Button>}> <br/> <br/>
                        </Timeline.Item>
                        <Timeline.Item label="German Progress"> <Tag color={"green"}> On Track </Tag> </Timeline.Item>
                        <Timeline.Item label="Level">
                            <small>A1</small> <small> 2033-02-08 / 2023-03-26 </small> <br />
                            <small>A2</small> <small> 2033-02-08 / 2023-03-26 </small> <br />
                        </Timeline.Item>
                        <Timeline.Item label="Exam Preparation">
                            <small>Goethe</small> <br />
                            <small> Date: 2033-02-08 </small> <br />
                            <small> S (5) </small>
                            <small> R (3) </small>
                            <small> W (5) </small>
                            <small> L (6) </small>
                        </Timeline.Item>
                        <Timeline.Item label="Exams">
                            <small>Goethe</small> <br />
                            <small> City: Tehran</small> <br />
                            <small> Due Date: 2033-02-08 </small> <br />
                            <small> Date: 2033-02-08 </small> <br />
                        </Timeline.Item>
                        <Timeline.Item label="B2 Exam Passed ?" children={
                            'test'
                        }>
                            <Tag color="green">Yes</Tag>  <br />
                            <small> Date: 2033-02-08 </small>
                        </Timeline.Item>
                    </Timeline>
                </Col>

            </Row>
        </>


    );


}
export default CustomCandidateTimeline;



