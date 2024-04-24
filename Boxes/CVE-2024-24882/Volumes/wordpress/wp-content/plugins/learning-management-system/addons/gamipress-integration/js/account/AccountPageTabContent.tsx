import { Heading, HStack, Image, Stack, Text } from '@chakra-ui/react';
import { __ } from '@wordpress/i18n';
import React from 'react';
import { Col, Row } from 'react-grid-system';
import { BiInfoCircle } from 'react-icons/bi';
import CountBox from '../../../../assets/js/account/components/CountBox';
import { isEmpty } from '../../../../assets/js/back-end/utils/utils';
import PlacementsInAccountPage from '../components/PlacementsInAccountPage';
import { AccountPageLocation } from '../enums/enums';

interface Props {
	placement: UiPlacementData;
}

const AccountPageTabContent: React.FC<Props> = (props) => {
	const { placement } = props;

	return (
		<PlacementsInAccountPage
			placementId={placement.id}
			location={AccountPageLocation.NEW_TAB}
			renderPoint={(pointTypeData) => (
				<Col lg={4} md={12} sm={12}>
					<CountBox
						title={pointTypeData.plural_name}
						count={pointTypeData.points}
						icon={
							pointTypeData.image_url ? (
								<Image
									src={pointTypeData.image_url}
									width="100%"
									height="100%"
									borderRadius="10px"
								/>
							) : null
						}
						noIconBackground
						colorScheme="gray"
					/>
				</Col>
			)}
			renderRank={(rankTypeData) => (
				<Col lg={4} md={12} sm={12}>
					<CountBox
						title={rankTypeData.singular_name}
						count={rankTypeData.rank}
						icon={
							rankTypeData.image_url ? (
								<Image
									src={rankTypeData.image_url}
									width="100%"
									height="100%"
									borderRadius="10px"
								/>
							) : null
						}
						noIconBackground
						colorScheme="cyan"
					/>
				</Col>
			)}
			renderAchievement={(achievementData) => (
				<Col lg={4} md={12} sm={12}>
					<CountBox
						title={achievementData.label}
						count={''}
						icon={
							achievementData.image_url ? (
								<Image
									src={achievementData.image_url}
									width="100%"
									height="100%"
									borderRadius="10px"
								/>
							) : null
						}
						noIconBackground
						colorScheme="cyan"
					/>
				</Col>
			)}
			wrapPlacementGroup={(contents, placement) => (
				<Stack direction="column" spacing="8">
					<Stack
						direction={{
							base: 'column',
							sm: 'row',
							md: 'row',
							lg: 'row',
						}}
						spacing="4"
						justify={{
							base: 'start',
							sm: 'start',
							md: 'start',
							lg: 'space-between',
						}}
						alignItems={{
							base: 'left',
							sm: 'left',
							md: 'center',
							lg: 'center',
						}}
					>
						<Heading size="md">{placement.title}</Heading>
					</Stack>
					{isEmpty(contents) ? (
						<HStack bgColor="gray.100" p="2">
							<BiInfoCircle />
							<Text fontWeight="md">{__('Not found.', 'masteriyo')}</Text>
						</HStack>
					) : (
						<Row
							gutterWidth={30}
							justify="start"
							direction="row"
							style={{ gap: 2, rowGap: '20px' }}
						>
							{contents}
						</Row>
					)}
				</Stack>
			)}
		/>
	);
};

export default AccountPageTabContent;
