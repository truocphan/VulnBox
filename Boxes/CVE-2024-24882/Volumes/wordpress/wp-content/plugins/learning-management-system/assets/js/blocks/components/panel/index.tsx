import { useState } from '@wordpress/element';
import classnames from 'classnames';
import React from 'react';
import Icon from '../icon';
import './editor.scss';

interface PropsType {
	title: string;
	initialOpen?: boolean;
}

const Panel: React.FC<PropsType> = (props) => {
	const { children, title, initialOpen = false } = props;
	const [isOpen, setIsOpen] = useState(initialOpen);

	return (
		<div className={classnames('masteriyo-panel', { 'is-open': isOpen })}>
			<div className="masteriyo-panel-head">
				<button
					onClick={() => setIsOpen(!isOpen)}
					className="masteriyo-panel-toggle-button"
				>
					<span className="masteriyo-panel-title">{title || ''}</span>
					<span className="masteriyo-panel-icon">
						{isOpen ? (
							<Icon type="controlIcon" name="chevron-up-circle" />
						) : (
							<Icon type="controlIcon" name="chevron-down-circle" />
						)}
					</span>
				</button>
			</div>
			{isOpen && <div className="masteriyo-panel-body">{children}</div>}
		</div>
	);
};

export default Panel;
