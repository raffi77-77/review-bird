import {useState} from "@wordpress/element";

export default function Flow({id, data}) {
    const [step, setStep] = useState(1);

    return <div>
        {step === 1 &&
            <div>
                <p>step 1 content</p>
            </div>}
        {step === 2 &&
            <div>
                <p>step 2 content</p>
            </div>}
        ...
    </div>
}