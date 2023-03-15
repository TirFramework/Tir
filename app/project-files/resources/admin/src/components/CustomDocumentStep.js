import {Steps} from 'antd';
import {Link, Redirect, useHistory} from "react-router-dom";
import {
    UserOutlined,
    CheckCircleOutlined,
    IssuesCloseOutlined,
    CloseCircleOutlined,
    InfoCircleOutlined
} from "@ant-design/icons";
import {useState} from "react";

const description = 'This is a description.';
const App = (props) => {
    const history = useHistory()

    const steps = props.options.steps
    const info = props.options.info
    const onChange = (value) => {
        let step = steps[value];
        history.push(step.link);
    }
    const customStep = steps.map((step, index) => {
        if (step.status === 'rejected') {
            steps[index].icon = <CloseCircleOutlined/>
        }
        if (step.status === 'finish') {
            steps[index].icon = <IssuesCloseOutlined/>
        }
        if (step.status === 'approved') {
            steps[index].icon = <CheckCircleOutlined/>
        }
    })

    return (
        <>
            <div className="info">
                <InfoCircleOutlined/>
                <Link to={info.link}> {info.title} </Link>
            </div>
            <Steps
                direction="vertical"
                onChange={onChange}
                size="small"
                current={props.options.currentStep}
                items={steps}
            />
        </>
    )
}
export default App;

