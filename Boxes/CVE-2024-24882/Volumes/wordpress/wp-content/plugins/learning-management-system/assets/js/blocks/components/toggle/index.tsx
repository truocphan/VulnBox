import { useInstanceId } from '@wordpress/compose';
import classnames from 'classnames';
import PropTypes from 'prop-types';
import React from 'react';
import './editor.scss';

interface PropTypes {
	checked: boolean;
	onChange: CallableFunction;
	label?: string;
}

const Toggle: React.FC<PropTypes> = (props) => {
	const { checked, onChange, label } = props;
	const id = useInstanceId(Toggle);

	return (
		<div
			className={classnames(
				'masteriyo-control',
				'masteriyo-toggle',
				'masteriyo-inline',
				{ 'is-checked': checked }
			)}
		>
			<div className="masteriyo-toggle-head">
				{label && (
					<label
						htmlFor={`masteriyo-toggle-${id}`}
						className="masteriyo-control-label masteriyo-toggle-label"
					>
						{label}
					</label>
				)}
			</div>

			<div className="masteriyo-control-body masteriyo-toggle-body">
				<input
					id={`masteriyo-toggle-${id}`}
					type="checkbox"
					className="masteriyo-toggle-checkbox"
					onChange={(e) => onChange(e.target.checked)}
				/>
				<span className="masteriyo-toggle-track" />
				<span className="masteriyo-toggle-thumb" />
			</div>
		</div>
	);
};

export default Toggle;
