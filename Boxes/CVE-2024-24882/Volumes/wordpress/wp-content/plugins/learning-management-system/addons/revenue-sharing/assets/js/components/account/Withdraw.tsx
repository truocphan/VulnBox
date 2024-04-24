import { Box, Tab, TabList, TabPanel, TabPanels, Tabs } from '@chakra-ui/react';
import { __ } from '@wordpress/i18n';
import React from 'react';
import WithdrawDetail from './components/WithdrawDetail';
import WithdrawsHistory from './components/WithdrawsHistory';

const Withdraw: React.FC = () => {
	const tabStyles = {
		fontWeight: 'medium',
		py: ['2', '4'],
		fontSize: ['xs', null, 'sm'],
		px: ['1', '2', '4'],
	};
	return (
		<Box>
			<WithdrawDetail />
			<Box mt="10">
				<Tabs>
					<TabList borderBottom="1px" borderColor="gray.100" flexWrap="wrap">
						<Tab sx={tabStyles}>
							{__('Withdraw Requests History', 'masteriyo')}
						</Tab>
					</TabList>
					<TabPanels>
						<TabPanel px="0">
							<WithdrawsHistory />
						</TabPanel>
					</TabPanels>
				</Tabs>
			</Box>
		</Box>
	);
};

export default Withdraw;
