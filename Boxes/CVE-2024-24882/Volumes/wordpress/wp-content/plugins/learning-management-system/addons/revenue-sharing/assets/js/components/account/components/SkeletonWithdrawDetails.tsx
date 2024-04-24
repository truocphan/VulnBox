import {
	Box,
	Skeleton,
	SkeletonCircle,
	SkeletonText,
	Stack,
} from '@chakra-ui/react';
import React from 'react';

const SkeletonWithdrawsList: React.FC = () => {
	return (
		<Stack spacing="5">
			<Stack
				direction={{ base: 'column', sm: 'column', md: 'row', lg: 'row' }}
				spacing="5"
			>
				{[1, 2, 3].map((x) => (
					<Box p={6} borderWidth="1px" key={x} w="100%">
						<Stack direction="row">
							<Skeleton h="4rem" w="4rem" />
							<Stack flex={2} spacing="5">
								<SkeletonText noOfLines={1} />
								<SkeletonCircle size="4" />
							</Stack>
						</Stack>
					</Box>
				))}
			</Stack>
			<Skeleton noOfLines={1} height="40px" width="113px" />
		</Stack>
	);
};

export default SkeletonWithdrawsList;
