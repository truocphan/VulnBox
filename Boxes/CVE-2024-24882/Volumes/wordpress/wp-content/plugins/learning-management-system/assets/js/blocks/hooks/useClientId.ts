import { useEffect } from '@wordpress/element';

const useClientId = (
	id: string,
	callback: CallableFunction,
	attributes: any
) => {
	useEffect(() => {
		const ID = id.substr(0, 8);

		if (!attributes.clientId) {
			callback({ clientId: ID });
		} else if (attributes.clientId && attributes.clientId !== ID) {
			callback({ clientId: ID });
		}
		// eslint-disable-next-line react-hooks/exhaustive-deps
	}, []);
};

export default useClientId;
