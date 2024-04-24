import { useEffect, useRef, useState } from '@wordpress/element';
import classnames from 'classnames';
import React from 'react';
import './editor.scss';

interface PropsType {
	hasIcon?: boolean;
	className?: string;
	label?: string;
	children?: any[];
}

const TabPanel: React.FC<PropsType> = (props) => {
	const { children: tabs, hasIcon = false, className, label } = props;
	const firstTab = tabs && tabs?.length > 0 ? tabs[0] : null;
	const [isActive, setIsActive] = useState(firstTab?.props?.tabTitle);
	const tabPanelRef = useRef<any>();

	useEffect(() => {
		if (!tabs) {
			return;
		}
		if (
			!tabs.some(({ props }) =>
				['Layout', 'Settings', 'Advanced'].includes(props?.tabTitle + '')
			)
		) {
			return;
		}

		const sidebarPanel = tabPanelRef.current.closest('.components-panel');
		sidebarPanel?.setAttribute('data-masteriyo-inspector-controls', true);

		return () => {
			sidebarPanel?.removeAttribute('data-masteriyo-inspector-controls');
		};
	}, [isActive, tabs]);

	if (!tabs) {
		return null;
	}

	return (
		<div
			className={classnames(
				'masteriyo-tab-panel',
				{ 'has-icon': hasIcon },
				className
			)}
			ref={tabPanelRef}
		>
			<div className="masteriyo-tab-panel-head">
				{label && <label htmlFor="masteriyo-tab-panel">{label}</label>}
				<div className="masteriyo-tab-panel-menu" role="group">
					{tabs.map((tab) => {
						const tabTitle = tab.props ? tab.props.tabTitle : '';

						return (
							<button
								key={tabTitle}
								className={classnames('masteriyo-tab-panel-menu-item', {
									'is-active': tabTitle === isActive,
								})}
								onClick={() => {
									setIsActive(tabTitle);
								}}
							>
								<span className="masteriyo-tab-panel-menu-item-title">
									{tabTitle}
								</span>
							</button>
						);
					})}
				</div>
			</div>
			<div className="masteriyo-tab-panel-body">
				{tabs.map((tab) => (tab.props?.tabTitle === isActive ? tab : ''))}
			</div>
		</div>
	);
};

export default TabPanel;
