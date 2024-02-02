
EADPI.Alerts ??= 
{
    types: 
    {
        error: 'error',
        info: 'info',
        success: 'success'
    },

    push(type, message)
    {
        switch (type)
        {
            case this.types.error: alert('Erro: ' + message); break;
            case this.types.info: alert('Info: ' + message); break;
            case this.types.success: alert('Sucesso: ' + message); break;
        }
    },

    pushFromJsonResult(jsonDecoded)
    {
        if (jsonDecoded.error)
            this.push(this.types.error, jsonDecoded.error);

        if (jsonDecoded.info)
            this.push(this.types.info, jsonDecoded.info);

        if (jsonDecoded.success)
            this.push(this.types.success, jsonDecoded.success);
    }
};

Object.freeze(EADPI.Alerts.types);