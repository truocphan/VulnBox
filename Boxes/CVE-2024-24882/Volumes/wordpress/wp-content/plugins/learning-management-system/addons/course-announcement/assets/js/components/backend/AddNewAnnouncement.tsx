import {
	Box,
	Button,
	ButtonGroup,
	Container,
	Flex,
	Heading,
	Icon,
	List,
	ListItem,
	Stack,
	useBreakpointValue,
	useMediaQuery,
	useToast,
} from '@chakra-ui/react';
import { __ } from '@wordpress/i18n';
import React from 'react';
import { FormProvider, useForm } from 'react-hook-form';
import { BiChevronLeft, BiSolidMegaphone } from 'react-icons/bi';
import { useMutation, useQueryClient } from 'react-query';
import { useNavigate } from 'react-router';
import { Link } from 'react-router-dom';
import {
	Header,
	HeaderLeftSection,
	HeaderLogo,
} from '../../../../../../assets/js/back-end/components/common/Header';
import { navActiveStyles } from '../../../../../../assets/js/back-end/config/styles';
import routes from '../../../../../../assets/js/back-end/constants/routes';
import API from '../../../../../../assets/js/back-end/utils/api';
import { deepClean } from '../../../../../../assets/js/back-end/utils/utils';
import AnnouncementActionBtn from './components/AnnouncementActionBtn';
import CourseSelect from './components/CourseSelect';
import Description from './components/Description';
import Name from './components/Name';
import { urls } from './constants/urls';
import { AnnouncementSchema } from './types/announcement';

const headerTabStyles = {
	mr: '10',
	py: '6',
	d: 'flex',
	gap: 1,
	justifyContent: 'flex-start',
	alignItems: 'center',
	fontWeight: 'medium',
	fontSize: ['xs', null, 'sm'],
};

const AddNewAnnouncement: React.FC = () => {
	const toast = useToast();
	const queryClient = useQueryClient();
	const methods = useForm();
	const navigate = useNavigate();
	const announcementAPI = new API(urls.courseAnnouncement);
	const [isLargerThan992] = useMediaQuery('(min-width: 992px)');
	const buttonSize = useBreakpointValue(['sm', 'md']);

	const addAnnouncement = useMutation<AnnouncementSchema>((data) =>
		announcementAPI.store(data)
	);

	const onSubmit = (data: any) => {
		addAnnouncement.mutate(deepClean(data), {
			onSuccess: (data) => {
				toast({
					title: data.title + __(' has been added.', 'masteriyo'),
					status: 'success',
					isClosable: true,
				});
				queryClient.invalidateQueries(`announcementList`);
				navigate({
					pathname: routes.courseAnnouncement.edit.replace(
						':courseAnnouncementId',
						data.id + ''
					),
				});
			},

			onError: (error: any) => {
				const message: any = error?.message
					? error?.message
					: error?.data?.message;

				toast({
					title: __('Failed to create announcement.', 'masteriyo'),
					description: message ? `${message}` : undefined,
					status: 'error',
					isClosable: true,
				});
			},
		});
	};

	const FormButton = () => (
		<ButtonGroup>
			<AnnouncementActionBtn
				isLoading={addAnnouncement.isLoading}
				methods={methods}
				onSubmit={onSubmit}
			/>
			<Button
				size={buttonSize}
				variant="outline"
				isDisabled={addAnnouncement.isLoading}
				onClick={() =>
					navigate({
						pathname: routes.courseAnnouncement.list,
					})
				}
			>
				{__('Cancel', 'masteriyo')}
			</Button>
		</ButtonGroup>
	);

	return (
		<Stack direction="column" spacing="8" alignItems="center">
			<Header>
				<HeaderLeftSection>
					<HeaderLogo />
					<List
						display={['none', 'flex', 'flex']}
						flexDirection={['column', 'row', 'row', 'row']}
					>
						<ListItem mb="0">
							<Link to={routes.courseAnnouncement.add}>
								<Button
									color="gray.600"
									variant="link"
									sx={headerTabStyles}
									_active={navActiveStyles}
									rounded="none"
									isActive
								>
									<Icon as={BiSolidMegaphone} />
									{__('Add New Announcement', 'masteriyo')}
								</Button>
							</Link>
						</ListItem>
					</List>
				</HeaderLeftSection>
			</Header>
			<Container maxW="container.xl">
				<Stack direction="column" spacing="6">
					<ButtonGroup>
						<Link to={routes.courseAnnouncement.list}>
							<Button
								variant="link"
								_hover={{ color: 'primary.500' }}
								leftIcon={<Icon fontSize="xl" as={BiChevronLeft} />}
							>
								{__('Back to Announcements', 'masteriyo')}
							</Button>
						</Link>
					</ButtonGroup>
					<FormProvider {...methods}>
						<form onSubmit={methods.handleSubmit(onSubmit)}>
							<Stack
								direction={['column', 'column', 'column', 'row']}
								spacing="8"
							>
								<Box
									flex="1"
									bg="white"
									p="10"
									shadow="box"
									display="flex"
									flexDirection="column"
									justifyContent="space-between"
								>
									<Stack direction="column" spacing="8">
										<Flex align="center" justify="space-between">
											<Heading as="h1" fontSize="x-large">
												{__('Add New Announcement', 'masteriyo')}
											</Heading>
										</Flex>

										<Stack direction="column" spacing="6">
											<Name />
											<Description />

											{isLargerThan992 ? <FormButton /> : null}
										</Stack>
									</Stack>
								</Box>
								<Box w={{ lg: '400px' }} bg="white" p="10" shadow="box">
									<Stack direction="column" spacing="6">
										<CourseSelect />
										{!isLargerThan992 ? <FormButton /> : null}
									</Stack>
								</Box>
							</Stack>
						</form>
					</FormProvider>
				</Stack>
			</Container>
		</Stack>
	);
};

export default AddNewAnnouncement;
