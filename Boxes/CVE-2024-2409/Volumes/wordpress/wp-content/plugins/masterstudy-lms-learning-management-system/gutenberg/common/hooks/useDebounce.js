import { useEffect, useState } from '@wordpress/element';

export const useDebounce = ( value, delay = 1000 ) => {
  const [ debounceValue, setDebounceValue ] = useState( value );

  useEffect( () => {
    const handler = setTimeout( () => {
      setDebounceValue( value );
    }, delay );

    return () => clearTimeout( handler );
  }, [ value ] );

  return debounceValue;
};