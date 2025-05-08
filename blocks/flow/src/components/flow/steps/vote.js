import {useEffect, useState} from "@wordpress/element";
import StepLayout from "./components/layout";
import {CreateReview} from "../../../rest/rest";

export default function Vote({flowId, flowData, setStep}) {
    const [loading, setLoading] = useState(0);
    const [question, setQuestion] = useState('');

    useEffect(() => {
        if (flowData?.metas?.length) {
            for (const meta of flowData.metas) {
                // Question
                if (meta.key === 'question') {
                    if (meta.value) {
                        setQuestion(meta.value.replace('{site-name}', ReviewBird.site.name));
                    } else {
                        setQuestion('');
                    }
                }
            }
        }
    }, [flowData]);

    const like = async () => {
        setLoading(prev => prev + 1);
        try {
            const res = await CreateReview(ReviewBird.rest.url, ReviewBird.rest.nonce, {
                flow_uuid: flowId,
                like: 1
            });
            // TODO - redirect
        } catch (e) {
            console.log(e);
        }
        setLoading(prev => prev - 1);
    }

    const dislike = () => {
        setStep('review');
    }

    return <StepLayout>
        <div className="rw-flow-title">
            <p className="rw-flow-title-in">{question}</p>
        </div>
        <div className="rw-flow-feedback-actions">
            <div className="rw-flow-feedback-actions-in">
                <button className='rw-flow-button rw-flow-button-feedback rw-flow-button-feedback-good'
                        onClick={like}>
                    <div className='rw-flow-button-feedback-in'>
                        <svg className='rw-flow-button-feedback-i' xmlns='http://www.w3.org/2000/svg'
                             fill='none' viewBox='0 0 24 24'>
                            <use href='#rw-flow-thumb-up'/>
                        </svg>
                    </div>
                </button>
                <button className='rw-flow-button rw-flow-button-feedback rw-flow-button-feedback-bad'
                        onClick={dislike}>
                    <div className='rw-flow-button-feedback-in'>
                        <svg className='rw-flow-button-feedback-i' xmlns='http://www.w3.org/2000/svg'
                             fill='none' viewBox='0 0 24 24'>
                            <use href='#rw-flow-thumb-down'/>
                        </svg>
                    </div>
                </button>
            </div>
        </div>
    </StepLayout>
}