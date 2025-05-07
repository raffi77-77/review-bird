import {useState} from "@wordpress/element";
import StepLayout from "./components/layout";
import {CreateReview} from "../../../rest/rest";

export default function ReviewWithStars({flowId, reviewGating, setStep}) {
    const [loading, setLoading] = useState(0);
    const [rating, setRating] = useState(0);
    const [name, setName] = useState('');
    const [message, setMessage] = useState('');

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
            // TODO - redirect
        } catch (e) {
            console.log(e);
        }
        setLoading(prev => prev - 1);
    }

    return <div className="rw-flow-feedback-cont">
        <StepLayout>
            <div className="rw-flow-desc">
                <p className="rw-flow-desc-in">Es tut uns leid, dass wir Ihre Erwartungen nicht erfüllen konnten. Wie können wir es in Zukunft besser machen?</p>
            </div>
            <div className="rw-flow-label">
                <input type="text" placeholder="Geben Sie Ihren Namen ein (Optional)"
                       className="rw-flow-input"
                       onChange={e => setName(e.target.value)}/>
            </div>
            <div className="rw-flow-label">
                <div className={`rw-flow-stars${rating > 0 ? ` stars-${rating}` : ''}`}>
                    <div className="rw-flow-stars-item" onClick={() => setRating(1)}>
                        <svg className='rw-flow-stars-i' xmlns='http://www.w3.org/2000/svg'
                             fill='none' viewBox='0 0 24 24'>
                            <use href='#rw-flow-star'/>
                        </svg>
                    </div>
                    <div className="rw-flow-stars-item" onClick={() => setRating(2)}>
                        <svg className='rw-flow-stars-i' xmlns='http://www.w3.org/2000/svg'
                             fill='none' viewBox='0 0 24 24'>
                            <use href='#rw-flow-star'/>
                        </svg>
                    </div>
                    <div className="rw-flow-stars-item" onClick={() => setRating(3)}>
                        <svg className='rw-flow-stars-i' xmlns='http://www.w3.org/2000/svg'
                             fill='none' viewBox='0 0 24 24'>
                            <use href='#rw-flow-star'/>
                        </svg>
                    </div>
                    <div className="rw-flow-stars-item" onClick={() => setRating(4)}>
                        <svg className='rw-flow-stars-i' xmlns='http://www.w3.org/2000/svg'
                             fill='none' viewBox='0 0 24 24'>
                            <use href='#rw-flow-star'/>
                        </svg>
                    </div>
                    <div className="rw-flow-stars-item" onClick={() => setRating(5)}>
                        <svg className='rw-flow-stars-i' xmlns='http://www.w3.org/2000/svg'
                             fill='none' viewBox='0 0 24 24'>
                            <use href='#rw-flow-star'/>
                        </svg>
                    </div>
                </div>
            </div>
            <div className="rw-flow-label">
                <textarea placeholder="Schildern Sie uns Ihre Eindrücke und Erfahrungen (Optional)"
                          className="rw-flow-textarea"
                          onChange={e => setMessage(e.target.value)}/>
            </div>
            <div className="rw-flow-feedback-actions">
                <div className="rw-flow-feedback-actions-in">
                    <button
                        className={`rw-flow-button rw-flow-button-minimal rw-flow-button-secondary${loading > 0 ? ' rw-button-disabled' : ''}`}
                        onClick={() => setStep('vote')}>
                        <span className='rw-flow-button-desc'>Abbrechen</span>
                    </button>
                    <button
                        className={`rw-flow-button rw-flow-button-minimal rw-flow-button-primary${loading > 0 ? ' rw-button-disabled' : ''}`}
                        onClick={submit}>
                        <span className='rw-flow-button-desc'>Absenden</span>
                    </button>
                </div>
            </div>
        </StepLayout>
        {!reviewGating &&
            <div>
                <button className={`rw-flow-feedback-link${loading > 0 ? ' rw-button-disabled' : ''}`}
                        onClick={() => setStep('public-review')}>
                    <span>Überspringen und öffentlich posten</span>
                </button>
            </div>}
    </div>
}