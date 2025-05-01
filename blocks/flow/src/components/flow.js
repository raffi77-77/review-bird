import {useState} from "@wordpress/element";
import Utilities from "../utilities/index"
import logo from "../../../../resources/logo/logo.svg"

export default function Flow({id, data}) {
    const [step, setStep] = useState(1);

    return <div id="review-bird">
        <Utilities/>
        {step === 1 && <div className="rw-flow-container">

            <div className='rw-flow-row rw-flow-feedback-row none'>
                <div className="rw-flow-feedback-header">
                    <div className="rw-flow-logo">
                        <img src={logo}/>
                    </div>
                    <button className="rw-flow-button-close rw-flow-button-30">
                        <svg className='rw-flow-button-close-i' xmlns='http://www.w3.org/2000/svg'
                             fill='none' viewBox='0 0 24 24'>
                            <use href='#rw-flow-close'/>
                        </svg>
                    </button>
                </div>
                <div className="rw-flow-feedback-body">
                    <div className="rw-flow-title">
                        <p className="rw-flow-title-in"> Würden Sie {`{site-name}`} <br/> weiterempfehlen? </p>
                    </div>

                    <div className="rw-flow-feedback-actions">
                        <div className="rw-flow-feedback-actions-in">
                            <button
                                className='rw-flow-button rw-flow-button-feedback rw-flow-button-feedback-good'>
                                <div className='rw-flow-button-feedback-in'>
                                    <svg className='rw-flow-button-feedback-i' xmlns='http://www.w3.org/2000/svg'
                                         fill='none' viewBox='0 0 24 24'>
                                        <use href='#rw-flow-thumb-up'/>
                                    </svg>
                                </div>
                            </button>

                            <button
                                className='rw-flow-button rw-flow-button-feedback rw-flow-button-feedback-bad'>
                                <div className='rw-flow-button-feedback-in'>
                                    <svg className='rw-flow-button-feedback-i' xmlns='http://www.w3.org/2000/svg'
                                         fill='none' viewBox='0 0 24 24'>
                                        <use href='#rw-flow-thumb-down'/>
                                    </svg>
                                </div>
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <div className='rw-flow-row rw-flow-feedback-row none'>
                <div className="rw-flow-feedback-header">
                    <div className="rw-flow-logo">
                        <img src={logo}/>
                    </div>
                    <button className="rw-flow-button-close rw-flow-button-30">
                        <svg className='rw-flow-button-close-i' xmlns='http://www.w3.org/2000/svg'
                             fill='none' viewBox='0 0 24 24'>
                            <use href='#rw-flow-close'/>
                        </svg>
                    </button>
                </div>
                <div className="rw-flow-feedback-body">
                    <div className="rw-flow-desc">
                        <p className="rw-flow-desc-in"> Es tut uns leid, dass wir Ihre Erwartungen nicht erfüllen
                            konnten. Wie können wir es in Zukunft besser machen?</p>
                    </div>
                    <div className="rw-flow-label">
                        <input type="text" placeholder="Geben Sie Ihren Namen ein (Optional)"
                               className="rw-flow-input"/>
                    </div>
                    <div className="rw-flow-label">
                        <div className="rw-flow-stars">
                            <div className="rw-flow-stars-item">
                                <svg className='rw-flow-stars-i' xmlns='http://www.w3.org/2000/svg'
                                     fill='none' viewBox='0 0 24 24'>
                                    <use href='#rw-flow-star'/>
                                </svg>
                            </div>
                            <div className="rw-flow-stars-item">
                                <svg className='rw-flow-stars-i' xmlns='http://www.w3.org/2000/svg'
                                     fill='none' viewBox='0 0 24 24'>
                                    <use href='#rw-flow-star'/>
                                </svg>
                            </div>
                            <div className="rw-flow-stars-item">
                                <svg className='rw-flow-stars-i' xmlns='http://www.w3.org/2000/svg'
                                     fill='none' viewBox='0 0 24 24'>
                                    <use href='#rw-flow-star'/>
                                </svg>
                            </div>
                            <div className="rw-flow-stars-item">
                                <svg className='rw-flow-stars-i' xmlns='http://www.w3.org/2000/svg'
                                     fill='none' viewBox='0 0 24 24'>
                                    <use href='#rw-flow-star'/>
                                </svg>
                            </div>
                            <div className="rw-flow-stars-item">
                                <svg className='rw-flow-stars-i' xmlns='http://www.w3.org/2000/svg'
                                     fill='none' viewBox='0 0 24 24'>
                                    <use href='#rw-flow-star'/>
                                </svg>
                            </div>
                        </div>
                    </div>
                    <div className="rw-flow-label">
                        <textarea placeholder="Schildern Sie uns Ihr﻿e Eindrücke und Erfahrungen (Optional)"
                                  className="rw-flow-textarea"/>
                    </div>
                    <div className="rw-flow-feedback-actions">
                        <div className="rw-flow-feedback-actions-in">
                            <button className='rw-flow-button rw-flow-button-minimal rw-flow-button-secondary'>
                                <span className='rw-flow-button-desc'>Abbrechen</span>
                            </button>
                            <button className='rw-flow-button rw-flow-button-minimal rw-flow-button-primary'>
                                <span className='rw-flow-button-desc'>Abbrechen</span>
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <div className='rw-flow-row rw-flow-feedback-row rw-flow-feedback-public-review-row'>
                <div className="rw-flow-feedback-header">
                    <div className="rw-flow-logo">
                        <img src={logo}/>
                    </div>
                    <button className="rw-flow-button-close rw-flow-button-30">
                        <svg className='rw-flow-button-close-i' xmlns='http://www.w3.org/2000/svg'
                             fill='none' viewBox='0 0 24 24'>
                            <use href='#rw-flow-close'/>
                        </svg>
                    </button>
                </div>
                <div className="rw-flow-feedback-body">
                    <div className="rw-flow-title">
                        <p className="rw-flow-title-in">Hinterlassen Sie eine öffentliche Bewertung</p>
                    </div>

                    <div className="rw-flow-label">
                        <div className="rw-flow-public-review-desc">
                            <p className="rw-flow-public-review-desc-in">Mit Klick auf den unten stehenden Button
                                können Sie eine öffentliche Bewertung abgeben:</p>
                        </div>
                    </div>
                    <div className="rw-flow-feedback-actions rw-flow-platform-slide">
                        <div className="rw-flow-feedback-actions-in rw-flow-platform-slide-in">
                            <button className="rw-flow-button rw-flow-button-platform">
                                <svg className='rw-flow-button-platform-i' xmlns='http://www.w3.org/2000/svg'
                                     fill='none' viewBox='0 0 24 24'>
                                    <use href='#rw-flow-youtube'/>
                                </svg>
                            </button>
                            <button className="rw-flow-button rw-flow-button-platform">
                                <svg className='rw-flow-button-platform-i' xmlns='http://www.w3.org/2000/svg'
                                     fill='none' viewBox='0 0 24 24'>
                                    <use href='#rw-flow-wordpress'/>
                                </svg>
                            </button>
                            <button className="rw-flow-button rw-flow-button-platform">
                                <svg className='rw-flow-button-platform-i' xmlns='http://www.w3.org/2000/svg'
                                     fill='none' viewBox='0 0 24 24'>
                                    <use href='#rw-flow-forsquare'/>
                                </svg>
                            </button>
                            <button className="rw-flow-button rw-flow-button-platform">
                                <svg className='rw-flow-button-platform-i' xmlns='http://www.w3.org/2000/svg'
                                     fill='none' viewBox='0 0 24 24'>
                                    <use href='#rw-flow-google-play'/>
                                </svg>
                            </button>
                            <button className="rw-flow-button rw-flow-button-platform app-store">
                                <svg className='rw-flow-button-platform-i' xmlns='http://www.w3.org/2000/svg'
                                     fill='none' viewBox='0 0 24 24'>
                                    <use href='#rw-flow-app-store'/>
                                </svg>
                            </button>
                            <button className="rw-flow-button rw-flow-button-platform">
                                <svg className='rw-flow-button-platform-i' xmlns='http://www.w3.org/2000/svg'
                                     fill='none' viewBox='0 0 24 24'>
                                    <use href='#rw-flow-audible'/>
                                </svg>
                            </button>
                            <button className="rw-flow-button rw-flow-button-platform">
                                <svg className='rw-flow-button-platform-i' xmlns='http://www.w3.org/2000/svg'
                                     fill='none' viewBox='0 0 24 24'>
                                    <use href='#rw-flow-trustpilot'/>
                                </svg>
                            </button>
                            <button className="rw-flow-button rw-flow-button-platform">
                                <svg className='rw-flow-button-platform-i' xmlns='http://www.w3.org/2000/svg'
                                     fill='none' viewBox='0 0 24 24'>
                                    <use href='#rw-flow-doctolib'/>
                                </svg>
                            </button>
                            <button className="rw-flow-button rw-flow-button-platform">
                                <svg className='rw-flow-button-platform-i' xmlns='http://www.w3.org/2000/svg'
                                     fill='none' viewBox='0 0 24 24'>
                                    <use href='#rw-flow-jameda'/>
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <div className='rw-flow-row rw-flow-feedback-row none'>
                <div className="rw-flow-feedback-header">
                    <div className="rw-flow-logo">
                        <img src={logo}/>
                    </div>
                    <button className="rw-flow-button-close rw-flow-button-30">
                        <svg className='rw-flow-button-close-i' xmlns='http://www.w3.org/2000/svg'
                             fill='none' viewBox='0 0 24 24'>
                            <use href='#rw-flow-close'/>
                        </svg>
                    </button>
                </div>
                <div className="rw-flow-feedback-body">
                    <div className="rw-flow-title">
                        <p className="rw-flow-title-in">Hinterlassen Sie eine öffentliche Bewertung</p>
                    </div>

                    <div className="rw-flow-label">
                        <div className="rw-flow-public-review-desc">
                            <p className="rw-flow-public-review-desc-in">Mit Klick auf den unten stehenden Button
                                können Sie eine öffentliche Bewertung abgeben:</p>
                        </div>
                    </div>
                    <div className="rw-flow-feedback-actions">
                        <div className="rw-flow-feedback-actions-in">
                            <button className='rw-flow-button rw-flow-button-minimal rw-flow-button-primary'>
                                <span className='rw-flow-button-desc'>Ja, öff﻿entlich posten</span>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        }
        {step === 2 &&
            <div>
                <p>step 2 content</p>
            </div>}
        ...
    </div>
}