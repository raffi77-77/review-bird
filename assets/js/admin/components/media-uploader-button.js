import {useRef} from "@wordpress/element";
import {__} from "@wordpress/i18n";

export default function MediaUploaderButton({onSelect, className, children}) {
    const mediaFrameRef = useRef(null);

    const openMediaUploader = () => {
        if (mediaFrameRef.current) {
            mediaFrameRef.current.open();
            return;
        }

        mediaFrameRef.current = window.wp.media({
            title: __("Select or Upload Media", 'review-bird'),
            button: {text: __("Use this media", 'review-bird')},
            multiple: false,
        });

        mediaFrameRef.current.on('select', () => {
            const attachment = mediaFrameRef.current.state().get('selection').first().toJSON();
            if (onSelect) onSelect(attachment);
        });

        mediaFrameRef.current.open();
    };

    return <button type="button" className={className} onClick={openMediaUploader}>{children}</button>;
}
