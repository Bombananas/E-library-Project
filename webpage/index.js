
const messageElement = document.getElementById('statusMessage');
async function messages(message) {
    document.getElementById('statusMessageContainer').removeAttribute('hidden');
    messageElement.textContent = message
    setTimeout(() =>{
        message.setAttribute('hidden',true)
        messageElement.textContent = '';
    },3500);
}
async function insertLevel(event,action) {
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
            form.reset();
        }
    }catch (error) {
        await messages('An error has occurred!!')
        console.error('Error:' , error);
    }
    
}
document.addEventListener('submit', async (event) =>{
    event.preventDefault();
    await insertLevel(event,'submit-levelContent');
})