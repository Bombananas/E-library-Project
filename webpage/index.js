
const messageElement = document.getElementById('statusMessage');
async function messages(message) {
    document.getElementById('statusMessageContainer').hidden = false;
    messageElement.textContent = message
    setTimeout(() =>{
        document.getElementById('statusMessageContainer').hidden = true;
        messageElement.textContent = '';
    },4000);
}
async function insertLevel(action) {
    const form = document.getElementById('insert-levelForm');
    if (!form) throw new Error('The form Does not exis!');
    const data = new FormData(form);
    try{
        const responese = await fetch(`levelApi.php?action=${action}`,{
            method:'POST',
            body: data
        });
        if (!responese.ok){
            throw new Error('Network responese is broken ' + responese.statusText);
            await messages('Network is not responding!!')
        }
        const result = await responese.json();
        if (result.message.includes('successfully')){
            await messages('Intertion successfully.')
            form.reset();
        }
    }catch (error) {
        await messages('An error has occurred!!')
        console.error('Error:' , error);
    }
}
async function deleteLevel(id){
    try{
        const responese = await fetch(`levelApi.php?= delete-levelContent=${id}`,{
            method: 'POST',
            headers: {
                'Content-Type':'application/json'
            }
        });
        if (!responese.ok){
            throw new Error('Network responese is broken ' + responese.statusText);
            await messages('Network is not responding!!')
        }
        const result = await responese.json();
        await messages('Deletion is successfully');
    }catch (error){
        await messages('An error has occurred!!')
        console.error('Error:' , error);
    }
}
document.getElementById('Add-levelForm').addEventListener('click',()=>{
    document.addEventListener('submit',async (event) => {
            event.preventDefault();
            await insertLevel('submit-levelContent');
    })
})
