import {useEffect, useState} from "@wordpress/element";
import StepLayout from "./components/layout";
import {CreateReview} from "../../../rest/rest";

export default function Vote({flowId, flowData, setStep}) {
    const [loading, setLoading] = useState(0);
    const [liking, setLiking] = useState(false);
    const [question, setQuestion] = useState('');
    const [message, setMessage] = useState(false);

    useEffect(() => {
        if (flowData?.utility) {
            // Question
            setQuestion(flowData.utility.question?.replace('{site-name}', ReviewBird.site.name) || '');
        }
    }, [flowData?.utility]);

    const like = async () => {
        setLoading(prev => prev + 1);
        setLiking(true);
        let res;
        try {
            res = await CreateReview(ReviewBird.rest.url, ReviewBird.rest.nonce, {
                flow_uuid: flowId,
                like: 1
            });
        } catch (e) {
            console.log(e);
            setMessage(__("Send review failed.", 'review-bird'));
            setLiking(false);
            setLoading(prev => prev - 1);
            return;
        }
        // Redirect
        if (res?.target) {
            window.location.href = res.target;
        } else if (flowData.utility.targets?.length) {
            window.location.href = flowData.utility.targets[0].url;
        } else {
            setMessage(__("Review sent successfully.", 'review-bird'));
        }
    }

    const dislike = () => {
        setStep('review');
    }

    return <StepLayout logo={flowData?.utility?.thumbnail_url}>
        {!message ?
            <>
                <div className="rw-flow-title">
                    <p className="rw-flow-title-in">{question}</p>
                </div>
                <div className="rw-flow-feedback-actions">
                    <div className="rw-flow-feedback-actions-in">
                        <button
                            className={`rw-flow-button rw-flow-button-feedback rw-flow-button-feedback-good${liking ? ' active' : ''}`}
                            onClick={like} disabled={loading > 0}>
                            <div className='rw-flow-button-feedback-in'>
                                <svg className='rw-flow-button-feedback-i' xmlns='http://www.w3.org/2000/svg'
                                     fill='none' viewBox='0 0 24 24'>
                                    <use href='#rw-flow-thumb-up'/>
                                </svg>
                            </div>
                        </button>
                        <button className='rw-flow-button rw-flow-button-feedback rw-flow-button-feedback-bad'
                                onClick={dislike} disabled={loading > 0}>
                            <div className='rw-flow-button-feedback-in'>
                                <svg className='rw-flow-button-feedback-i' xmlns='http://www.w3.org/2000/svg'
                                     fill='none' viewBox='0 0 24 24'>
                                    <use href='#rw-flow-thumb-down'/>
                                </svg>
                            </div>
                        </button>
                    </div>
                </div>
            </>
            :
            <div className="rw-flow-label">
                <div className="rw-flow-public-review-desc">
                    <p className="rw-flow-public-review-desc-in">{message}</p>
                </div>
            </div>}
    </StepLayout>
}