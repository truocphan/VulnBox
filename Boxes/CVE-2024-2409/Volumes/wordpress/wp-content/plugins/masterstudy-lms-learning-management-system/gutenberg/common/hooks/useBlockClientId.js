import { useEffect } from "@wordpress/element";

export const useBlockClientId = (attributes, setAttributes, clientId) => {
	useEffect(() => {
		if (
		  !attributes.clientId ||
		  (attributes.clientId && attributes.clientId !== clientId)
		) {
		  setAttributes({ clientId });
		}
	}, [attributes.clientId, setAttributes, clientId]);
}
