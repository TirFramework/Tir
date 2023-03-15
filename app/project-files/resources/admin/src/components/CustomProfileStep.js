import {Button, Steps} from 'antd';
import {Link} from "react-router-dom";
import axios from "../lib/axios";
import {UserOutlined, SolutionOutlined, LoadingOutlined, SmileOutlined} from "@ant-design/icons";

const {Step: CustomProfileStep} = Steps;



const App = (props) => {

    const pdf = async () => {
        const { data } = await axios({
            method: 'get',
            responseType: 'blob',
            url:'resume/pdf/'+props.options.id
        });
        return data;
    };

    if(props.options.id === null){
        return (<Steps current={props.options.currentStep} className={"mb-10"}>
            <CustomProfileStep title={<Link to="/admin/profile/create-edit">{props.options.firstStep.title}</Link>} description={props.options.firstStep.description}/>
            <CustomProfileStep title={props.options.secondStep.title} description={props.options.secondStep.description}/>
            <CustomProfileStep title={props.options.thirdStep.title} description={props.options.thirdStep.description}/>
        </Steps>)
    }
    return(
        <Steps current={props.options.currentStep} className={"mb-10"}>
            <CustomProfileStep title={<Link to={"/admin/profile/create-edit?id="+props.options.id}>{props.options.firstStep.title}</Link>} description={props.options.firstStep.description}/>
            <CustomProfileStep title={<Link to ={"/admin/resume/create-edit?id="+props.options.id}>{props.options.secondStep.title}</Link>} description={props.options.secondStep.description}/>
            <CustomProfileStep title={<Button onClick={()=>{
                pdf().then((data)=>{
                    const blob = new Blob([data], { type: 'application/pdf' });
                    const url = window.URL.createObjectURL(blob);
                    window.open(url);
                });
            }}>{props.options.thirdStep.title}</Button>} description={props.options.thirdStep.description}/>
        </Steps>

    );


}
export default App;



