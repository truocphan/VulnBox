import { useEffect, useRef } from '@wordpress/element';

const useClickOutside = (
	elRef: React.MutableRefObject<any>,
	callback: CallableFunction,
	extraElRef: React.MutableRefObject<any> | null = null
) => {
	const callbackRef = useRef<any>();
	callbackRef.current = callback;

	useEffect(() => {
		const handleClickOutside = (e: any) => {
			if (
				!document.body.contains(elRef.current) ||
				!elRef.current ||
				elRef.current.contains(e.target) ||
				!callbackRef.current ||
				(null !== extraElRef && extraElRef.current.contains(e.target))
			) {
				return;
			}

			callbackRef.current(e);
		};
		document.addEventListener('click', handleClickOutside, true);

		return () => {
			document.removeEventListener('click', handleClickOutside, true);
		};
	}, [callbackRef, elRef, extraElRef]);
};

export default useClickOutside;
