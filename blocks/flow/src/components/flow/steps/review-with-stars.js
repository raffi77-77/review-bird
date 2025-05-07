import {useState} from "@wordpress/element";
import StepLayout from "./components/layout";

export default function ReviewWithStars({setStep}) {
    const [stars, setStars] = useState(0);
    const [name, setName] = useState('');
    const [impressionsExperiences, setImpressionsExperiences] = useState('');

    const submit = () => {
        // TODO - Call for dislike
    }

    return <>
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
                <div className={`rw-flow-stars${stars > 0 ? ` stars-${stars}` : ''}`}>
                    <div className="rw-flow-stars-item" onClick={() => setStars(1)}>
                        <svg className='rw-flow-stars-i' xmlns='http://www.w3.org/2000/svg'
                             fill='none' viewBox='0 0 24 24'>
                            <use href='#rw-flow-star'/>
                        </svg>
                    </div>
                    <div className="rw-flow-stars-item" onClick={() => setStars(2)}>
                        <svg className='rw-flow-stars-i' xmlns='http://www.w3.org/2000/svg'
                             fill='none' viewBox='0 0 24 24'>
                            <use href='#rw-flow-star'/>
                        </svg>
                    </div>
                    <div className="rw-flow-stars-item" onClick={() => setStars(3)}>
                        <svg className='rw-flow-stars-i' xmlns='http://www.w3.org/2000/svg'
                             fill='none' viewBox='0 0 24 24'>
                            <use href='#rw-flow-star'/>
                        </svg>
                    </div>
                    <div className="rw-flow-stars-item" onClick={() => setStars(4)}>
                        <svg className='rw-flow-stars-i' xmlns='http://www.w3.org/2000/svg'
                             fill='none' viewBox='0 0 24 24'>
                            <use href='#rw-flow-star'/>
                        </svg>
                    </div>
                    <div className="rw-flow-stars-item" onClick={() => setStars(5)}>
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
                          onChange={e => setImpressionsExperiences(e.target.value)}/>
            </div>
            <div className="rw-flow-feedback-actions">
                <div className="rw-flow-feedback-actions-in">
                    <button className='rw-flow-button rw-flow-button-minimal rw-flow-button-secondary'
                            onClick={() => setStep('vote')}>
                        <span className='rw-flow-button-desc'>Abbrechen</span>
                    </button>
                    <button className='rw-flow-button rw-flow-button-minimal rw-flow-button-primary' onClick={submit}>
                        <span className='rw-flow-button-desc'>Abbrechen</span>
                    </button>
                </div>
            </div>
        </StepLayout>
        {/*TODO - Link here*/}
        <div>
            <button onClick={() => setStep('public-review')}>Uberspringen und offentich posten</button>
        </div>
    </>
}