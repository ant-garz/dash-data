<script>
	import Layout from "./+layout.svelte";
	import {
		Card,
		CardBody,
		CardTitle,
		FormGroup,
		Label,
		Input,
		Button,
		Alert,
		Container,
		Row,
		Col,
	} from "@sveltestrap/sveltestrap";
	import { theme } from "../stores/theme";
	import api from "../api"
	import { onMount } from "svelte";

	$: currentTheme = $theme;

    export let id; // prop from laravel
	let title = "";
	let codec = "";
	let format = "";
	let duration = "";
	let width = "";
	let height = "";
	let size = "";
	let recorded_at = "";

	let error = "";
	let success = "";

	// Parse video ID from current URL (e.g. /videos/123/edit)
	onMount(async () => {


		try {
			const res = await api.get(`/api/videos/${id}`);
			const video = res.data;
			title = video.title || "";
			codec = video.codec;
			format = video.format;
			duration = video.duration;
			width = video.width;
			height = video.height;
			size = video.size;
			recorded_at = video.recorded_at;
		} catch (err) {
			error = "Failed to load video data.";
		}
	});

	async function handleSubmit(e) {
		e.preventDefault();
		error = success = "";

		try {
			await api.put(`/api/videos/${id}`, {
				title,
				codec,
				format,
				duration,
				width,
				height,
				size,
				recorded_at,
			});
			success = "Video updated successfully.";
		} catch (err) {
			error = "Failed to update video.";
		}
	}
</script>

<Layout>
	<Container class="py-5">
		<Row class="justify-content-center">
			<Col sm="12" md="8" lg="6">
				<Card theme={currentTheme === "dark" ? "dark" : "light"}>
					<CardBody>
						<CardTitle tag="h4" class="mb-4 text-center">Edit Video</CardTitle>

						{#if error}
							<Alert color="danger">{error}</Alert>
						{/if}
						{#if success}
							<Alert color="success">{success}</Alert>
						{/if}

						<form on:submit={handleSubmit}>
							<FormGroup>
								<Label for="title">Title</Label>
								<Input id="title" bind:value={title} required />
							</FormGroup>

							<FormGroup>
								<Label for="codec">Codec</Label>
								<Input id="codec" value={codec} disabled />
							</FormGroup>

							<FormGroup>
								<Label for="format">Format</Label>
								<Input id="format" value={format} disabled />
							</FormGroup>

							<FormGroup>
								<Label for="duration">Duration (sec)</Label>
								<Input id="duration" type="number" value={duration} disabled />
							</FormGroup>

							<FormGroup>
								<Label for="dimensions">Resolution</Label>
								<Input id="dimensions" value={`${width} × ${height}`} disabled />
							</FormGroup>

							<FormGroup>
								<Label for="size">File Size (bytes)</Label>
								<Input id="size" type="number" value={size} disabled />
							</FormGroup>

							<FormGroup>
								<Label for="recorded_at">Recorded At</Label>
								<Input
									id="recorded_at"
									type="datetime-local"
									value={recorded_at?.slice(0, 16)}
									disabled
								/>
							</FormGroup>

							<Button color="primary" class="w-100 mt-3" type="submit">
								Save Changes
							</Button>
						</form>
					</CardBody>
				</Card>
			</Col>
		</Row>
	</Container>
</Layout>
