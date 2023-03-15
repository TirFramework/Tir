import {Button, Form, Input, Modal} from "antd";
import React, {useEffect, useRef, useState} from 'react';
import SignaturePad from 'react-signature-canvas';
import { separationRules } from "../lib/helpers";


const SignatureModal = ({onChange, ...props})=> {
    let padRef = useRef({})

    const [openModel, setOpenModal] = useState(false)
    const [imageURL, setImageURL] = useState([props.value])
    const [signature, setSignature] = useState(null)

    console.log('imageUrl' , imageURL);
    const handelChange = () => {
        setSignature(padRef.current.getTrimmedCanvas().toDataURL('image/png'))
    }

    const create = () => {
        onChange(signature)
        setImageURL(signature)
        setOpenModal(false)
    }

    function handleClear () {
        padRef.current.clear();
    }



    return (
        <div className={'signature-input'}>
            <Button onClick={() => setOpenModal(true)}>{props.display}</Button>
            <br />

            {imageURL != '' &&
                <>
                    <img
                        src={imageURL}
                        alt='signature'
                        className='signature'
                    />
                </>
            }

            {openModel &&
                <div className='modalContainer'>
                    <div className='modal'>
                        <div className='sigPadContainer'>
                                <SignaturePad
                                    ref={padRef}
                                    penColor={'blue'}
                                    canvasProps={{className: 'sigCanvas'}}
                                    onEnd = {()=>{
                                        handelChange()
                                    }}
                                    minDistance={3}
                                    throttle = {4}
                                    velocityFilterWeight={.7}
                                    minWidth = {1}
                                    maxWidth = {1.5}
                                />
                        </div>


                        <div className='modal-bottom'>
                            <Button onClick={() => setOpenModal(false)}>Cancel</Button>
                            <Button type="primary" className='create' onClick={create}> Create </Button>
                        </div>
                    </div>
                </div>
            }

        </div>

    )
}


const CustomSignature = (props) => {

    const rules = separationRules({
    pageType: props.pageType,
    rules: props.rules,
    creationRules: props.creationRules,
    updateRules: props.updateRules,
  });


  return (
    <>
        <Form.Item
            label={props.display}
            name={props.name}
            initialValue={props.value}
            rules={rules}
        >

        <SignatureModal
            onChange={(value)=>{}}
            {...props}
        />

        </Form.Item>

    </>
  );
};

export default CustomSignature;
