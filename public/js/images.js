window.onload = () => {
    // deal with btn delete
    let links = document.querySelectorAll("[data-delete]")
    console.log(links)

    // loop through links
    
    for (link of links) {
        // on click
        link.addEventListener("click", function(e) {
            // block nav
            e.preventDefault();
            //  confirm
            if (confirm("Dude, you want to delete that pic ??")) {
                // send ajax request to link href with method delete
                fetch(this.getAttribute('href'), {
                    method: 'DELETE',
                    headers:{
                         "X-Requested-With": "XMLHttpRequest",
                         "Content-Type": "application/json"
                        },
                        body: JSON.stringify({"_token": this.dataset.token })
                }).then(
                    // get response in JSON
                    response => response.json()
                ).then(data => {
                    if (data.success) {
                        this.parentElement.remove()
                    }else{
                        alert(data.error)
                    }
                }).catch(e => alert(e));
            }
            
        });
    }
}