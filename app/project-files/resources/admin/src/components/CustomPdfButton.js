import {Button, notification, Steps} from 'antd';
import axios from "../lib/axios";
import * as api from "../api";



const App = (props) => {

    const pdf = async () => {
        const { data } = await axios({
            method: 'get',
            responseType: 'blob',
            url:props?.options?.url
        });
        return data;
    };


    return(
        <Button type="primary" onClick={()=>{
            pdf().then((data)=>{
                const blob = new Blob([data], { type: 'application/pdf' });
                const url = window.URL.createObjectURL(blob);
                window.open(url);
            });
        }}> {props.display}</Button>
    );
}
export default App;

