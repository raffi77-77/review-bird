import {useEffect, useState} from "@wordpress/element";
import {__} from "@wordpress/i18n";
import StepLayout from "./components/layout";
import {CreateReview} from "../../../rest/rest";

export default function ReviewWithStars({flowId, flowData, setStep}) {
    const [loading, setLoading] = useState(0);
    const [negativeReviewBoxText, setNegativeReviewBoxText] = useState('');
    const [negativeReviewSuccessMessage, setNegativeReviewSuccessMessage] = useState('');
    const [namePlaceholderText, setNamePlaceholderText] = useState('');
    const [messagePlaceholderText, setMessagePlaceholderText] = useState('');
    const [reviewGating, setReviewGating] = useState(false);
    const [rating, setRating] = useState(0);
    const [name, setName] = useState('');
    const [message, setMessage] = useState('');
    const [wantedRate, setWantedRate] = useState(0);

    useEffect(() => {
        if (flowData?.metas?.length) {
            for (const meta of flowData.metas) {
                // Negative review box text
                if (meta.key === 'negative_review_box_text') {
                    if (meta.value) {
                        setNegativeReviewBoxText(meta.value.replace('{site-name}', ReviewBird.site.name));
                    }
                }
                // Negative review name placeholder text
                if (meta.key === 'negative_review_name_placeholder_text') {
                    if (meta.value) {
                        setNamePlaceholderText(meta.value);
                    }
                }
                // Negative review message placeholder text
                if (meta.key === 'negative_review_message_placeholder_text') {
                    if (meta.value) {
                        setMessagePlaceholderText(meta.value);
                    }
                }
                // Negative review success message
                if (meta.key === 'negative_review_success_message') {
                    if (meta.value) {
                        setNegativeReviewSuccessMessage(meta.value);
                    }
                }
                // Review gating
                if (meta.key === 'review_gating_off') {
                    setReviewGating(!meta.value);
                }
            }
        }
    }, [flowData]);

    const submit = async () => {
        setLoading(prev => prev + 1);
        try {
            const res = await CreateReview(ReviewBird.rest.url, ReviewBird.rest.nonce, {
                flow_uuid: flowId,
                like: 0,
                name,
                message,
                rating
            });
            if (negativeReviewSuccessMessage) {
                alert(negativeReviewSuccessMessage);
            }
        } catch (e) {
            console.log(e);
        }
        setLoading(prev => prev - 1);
    }

    return <div className="rw-flow-feedback-cont">
        <StepLayout>
            <div className="rw-flow-desc">
                <p className="rw-flow-desc-in">{negativeReviewBoxText}</p>
            </div>
            <div className="rw-flow-label">
                <input type="text" placeholder={namePlaceholderText}
                       className="rw-flow-input"
                       onChange={e => setName(e.target.value)}/>
            </div>
            <div className="rw-flow-label">
                <div className={`rw-flow-stars${rating > 0 ? ` stars-${rating}` : ''}`}>
                    <div className="rw-flow-stars-item" onClick={() => setRating(1)}>
                        <svg className={`rw-flow-stars-i${wantedRate ? ' wanted' : ''}`}
                             xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 24 24'
                             onMouseEnter={() => setWantedRate(1)} onMouseLeave={() => setWantedRate(0)}>
                            <use href='#rw-flow-star'/>
                        </svg>
                    </div>
                    <div className="rw-flow-stars-item" onClick={() => setRating(2)}>
                        <svg className={`rw-flow-stars-i${wantedRate > 1 ? ' wanted' : ''}`}
                             xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 24 24'
                             onMouseEnter={() => setWantedRate(2)} onMouseLeave={() => setWantedRate(0)}>
                            <use href='#rw-flow-star'/>
                        </svg>
                    </div>
                    <div className="rw-flow-stars-item" onClick={() => setRating(3)}>
                        <svg className={`rw-flow-stars-i${wantedRate > 2 ? ' wanted' : ''}`}
                             xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 24 24'
                             onMouseEnter={() => setWantedRate(3)} onMouseLeave={() => setWantedRate(0)}>
                            <use href='#rw-flow-star'/>
                        </svg>
                    </div>
                    <div className="rw-flow-stars-item" onClick={() => setRating(4)}>
                        <svg className={`rw-flow-stars-i${wantedRate > 3 ? ' wanted' : ''}`}
                             xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 24 24'
                             onMouseEnter={() => setWantedRate(4)} onMouseLeave={() => setWantedRate(0)}>
                            <use href='#rw-flow-star'/>
                        </svg>
                    </div>
                    <div className="rw-flow-stars-item" onClick={() => setRating(5)}>
                        <svg className={`rw-flow-stars-i${wantedRate > 4 ? ' wanted' : ''}`}
                             xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 24 24'
                             onMouseEnter={() => setWantedRate(5)} onMouseLeave={() => setWantedRate(0)}>
                            <use href='#rw-flow-star'/>
                        </svg>
                    </div>
                </div>
            </div>
            <div className="rw-flow-label">
                <textarea placeholder={messagePlaceholderText}
                          className="rw-flow-textarea"
                          onChange={e => setMessage(e.target.value)}/>
            </div>
            <div className="rw-flow-feedback-actions">
                <div className="rw-flow-feedback-actions-in">
                    <button
                        className={`rw-flow-button rw-flow-button-minimal rw-flow-button-secondary${loading > 0 ? ' rw-button-disabled' : ''}`}
                        onClick={() => setStep('vote')}>
                        <span className='rw-flow-button-desc'>{__("Cancel", 'review-bird')}</span>
                    </button>
                    <button
                        className={`rw-flow-button rw-flow-button-minimal rw-flow-button-primary${loading > 0 ? ' rw-button-disabled' : ''}`}
                        onClick={submit}>
                        <span className='rw-flow-button-desc'>{__("Submit", 'review-bird')}</span>
                    </button>
                </div>
            </div>
        </StepLayout>
        {!reviewGating &&
            <div>
                <button className={`rw-flow-feedback-link${loading > 0 ? ' rw-button-disabled' : ''}`}
                        onClick={() => setStep('public-review')}>
                    <span>{__("Skip and post publicly", 'review-bird')}</span>
                </button>
            </div>}
    </div>
}