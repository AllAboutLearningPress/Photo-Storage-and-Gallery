export const notify = (body, level) => {
    document.dispatchEvent(new CustomEvent('notify', {
        detail: {
            body: body,
            level: level ? level : 'info'
        }
    }))
}

export const axiosError = (err) => {
    notify("Something went wrong. Please try again")
}

export const updatePhotoDetails = (data) => {
    axios
        .post(route("uploads.update-details"), data)
        .then((resp) => {
            notify("Photo Updated", 'success');
        })
        .catch((err) => {
            notify("Something went Wrong", "danger");
        });
}
