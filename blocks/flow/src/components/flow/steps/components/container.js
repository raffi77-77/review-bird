export default function Container({inside, children}) {
    return inside ? <div className='rw-flow-container'>{children}</div> : children;
}