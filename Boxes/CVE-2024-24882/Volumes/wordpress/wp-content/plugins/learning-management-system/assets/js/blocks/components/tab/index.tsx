import React from 'react';

interface TabPropsType {
	tabTitle: string;
}

const Tab: React.FC<TabPropsType> = ({ children }) => {
	return (
		<div className="masteriyo-tab">
			{Array.isArray(children) ? children.map((child) => child) : children}
		</div>
	);
};

export default Tab;
