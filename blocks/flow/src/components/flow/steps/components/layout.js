export default function StepLayout({logo, className, children}) {
    return <div className={`rw-flow-feedback-row${className ? ' ' + className : ''}`}>
        <div className="rw-flow-feedback-header">
            <div className="rw-flow-logo">
                <img src={logo} alt='logo'/>
            </div>
            {/*<button className="rw-flow-button-close rw-flow-button-30">
                <svg className='rw-flow-button-close-i' xmlns='http://www.w3.org/2000/svg'
                     fill='none' viewBox='0 0 24 24'>
                    <use href='#rw-flow-close'/>
                </svg>
            </button>*/}
        </div>
        <div className="rw-flow-feedback-body">
            {children}
        </div>
    </div>
}