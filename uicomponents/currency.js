addEventListener('load', function(){


class CurrencyInput extends HTMLElement{
    constructor(){
        super();
        const shadow = this.attachShadow({mode:'open'});
        const wrapper = document.createElement('span');
        wrapper.setAttribute('class', 'currency-input-container')
        const style = document.createElement('style');

        const input = document.createElement('input');
        input.setAttribute('type', 'number')
        input.setAttribute('class', 'currency-input-input')
        
        const buttons = document.createElement('span');
        buttons.setAttribute('class', 'currency-input-buttons')
        const up = document.createElement('button');
        up.setAttribute('class', 'currency-input-buttons-up')
        up.innerText = "+"
        
        const down = document.createElement('button');
        down.setAttribute('class', 'currency-input-buttons-down')
        down.innerText = "-"
        buttons.appendChild(up)
        buttons.appendChild(down)
        


        const symbol = document.createElement('span');
        symbol.setAttribute('class', 'currency-input-symbol')
        console.log( this.getAttribute('symbol') )
        symbol.innerText = this.getAttribute('symbol');

        wrapper.appendChild(input);
        wrapper.appendChild(buttons);
        wrapper.appendChild(symbol);

        /**
            display: flex;
            align-items: center;
         */
        style.textContent = `
            .currency-input-container{
                display: flex;
                flex-direction: row;
                align-items: center;
                border: 1px solid rgba(0,0,0,25%);
                border-radius: 10px;
            }
            .currency-input-input:hover{
                outline: none;
            }
            .currency-input-input{
                padding: 0;
                margin: 0;
                height: 40px;
                width: 100%;
                box-shadow: 1px 1px 2px lightslategrey;
                border-radius: 0;
                border: none;
                direction: rtl;
            }
            .currency-input-buttons{
                display: flex;
                flex-direction: column;
            }
            .currency-input-buttons-up, .currency-input-buttons-down{
                font-weight: bold;
                width: 30px;
                text-align:center;
            }
            .currency-input-symbol{
                background-color: lightgray;
                height: 40px;
                width: 40px;
                display: flex;
                align-items: center;
                justify-content: center;
                border-radius: 0px 10px 10px 0px;
                border-left: 0;
                box-shadow: 1px 1px 2px lightslategrey;
                font-weight: bold;
            }`;

        shadow.appendChild(style);
        shadow.appendChild(wrapper);
    }
}

customElements.define('currency-input', CurrencyInput);
})