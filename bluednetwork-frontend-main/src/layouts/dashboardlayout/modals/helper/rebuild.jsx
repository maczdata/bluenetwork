const rebuild = (data) =>{
    var keys = Object.keys(data)

    return keys.map((key, idx)=>{
        if(!key.includes('verified_at') && !key.includes('bvn') && key.includes('verified')){
            return{
                value: data[key]
            }
        }else return false
    }).filter(data => data && data)
}
export default rebuild