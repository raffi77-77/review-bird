export default function Container({inside = true, children}) {
    return inside ? <div className='rw-flow-container'>{children}</div> : children;
}