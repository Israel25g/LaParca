let dataTable;
let dataTableInitializaded=false;

const datatableOptions={
    pagelength:3,
    destroy:true
}

const initDataTable = async() =>{
if(dataTableInitializaded){
    dataTable.destroy();
}
    await listUsers();

    dataTable=$("#datatable_user").dataTable(datatableOptions);

    dataTableInitializaded = true;
};
const listUsers = async () => {
    try {
        const response = await fetch("https://jsonplaceholder.typicode.com/users");
        const users = await response.json();

        let content = `
                    <tr>
                <th>indice</th>
                <th>nombre</th>
                <th>correo</th>
                <th>empresa</th>
                <th>Ciudad</th>
                <th>personal ID</th>
                <th>ubicacion</th>
                <th>serie</th>
                <th>codigo</th>
            </tr>`;
        users.forEach((user, index) => {
            content += `

            <tr>
                <td>${index + 1}</td>
                <td>${user.name}</td>
                <td>${user.email}</td>
                <td>${user.company.name}</td>
                <td>${user.address.city}</td>
                <td>${user.address.zipcode}</td>
                <td>${user.address.street}</td>
                <td>${user.address.geo.lat}</td>
                <td>${user.address.geo.lng}</td>
            </tr>`;
        });
        const datatable_users = document.getElementById("datatable_users");
        datatable_users.innerHTML = content;

    } catch (ex) {
        alert(ex);
    }
};

window.addEventListener("load", async () => {
    await initDataTable();
});