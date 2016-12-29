/**
 * Created by tingfeng-key.com on 2016/11/2.
 */
var gameData = {
    //获取字体大小
    fontSize: function(grade, num){
        var size;
        switch (grade){
            case 3:
                size = this.fontSize3(num);
                break;
            case 4:
                size = this.fontSize4(num);
                break;
            case 5:
                size = this.fontSize5(num);
                break
            case 6:
                size = this.fontSize6(num);
                break;
            default:
                size = '0px';
                break;
        }
        return size;
    },
    fontSize3: function(number){
        if( number > 0 && number <= 8 ){
            return '7rem';
        }else if( number > 8 && number <= 64 ){
            return '5rem';
        }else if( number > 64 && number <= 512 ){
            return '3.5rem';
        }else if( number > 512 && number <= 8192 ){
            return '2.6rem';
        }else if( number >= 8192 && number <= 65536 ){
            return '2rem';
        }else if( number > 65536 ){
            return '1rem';
        }
    },
    fontSize4: function(number){
        if( number > 0 && number <= 8 ){
            return '5rem';
        }else if( number > 8 && number <= 64 ){
            return '4rem';
        }else if( number > 64 && number <= 512 ){
            return '2.8rem';
        }else if( number > 512 && number <= 8192 ){
            return '2rem';
        }else if( number >= 8192 && number <= 65536 ){
            return '1.6rem';
        }else if( number > 65536 ){
            return '1rem';
        }
    },
    fontSize5: function(number){
        if( number > 0 && number <= 8 ){
            return '2.5rem';
        }else if( number > 8 && number <= 64 ){
            return '2.5rem';
        }else if( number > 64 && number <= 512 ){
            return '1.5rem';
        }else if( number > 512 && number <= 8192 ){
            return '1.4rem';
        }else if( number >= 8192 && number <= 65536 ){
            return '1.2rem';
        }else if( number > 65536 ){
            return '1rem';
        }
    },
    fontSize6: function(number){
        if( number > 0 && number <= 8 ){
            return '3rem';
        }else if( number > 8 && number <= 64 ){
            return '2.5rem';
        }else if( number > 64 && number <= 512 ){
            return '1.5rem';
        }else if( number > 512 && number <= 8192 ){
            return '1.4rem';
        }else if( number >= 8192 && number <= 65536 ){
            return '1rem';
        }else if( number > 65536 ){
            return '0.2rem';
        }
    },
    //获取字体颜色
    style: function(style, num){
        var color;
        switch (style){
            case 1:
                color = this.colour1(num);
                break;
            case 2:
                color = this.colour2(num);
                break;
            case 3:
                color = this.colour3(num);
                break;
            case 4:
                color = this.colour4(num);
                break;
            default:
                color = '#000';
                break;
        }
        return color;
    },
    bg: function(style, num){
        var color;
        switch (style){
            case 1:
                color = this.colourBg1(num);
                break;
            case 2:
                color = this.colourBg2(num);
                break;
            case 3:
                color = this.colourBg3(num);
                break;
            case 4:
                color = this.colourBg4(num);
                break;
            default:
                color = '#000';
                break;
        }
        return color;
    },
    //原版2048
    colour1: function(number){
        if( number > 0 && number <= 4 ){
            return '#7a7067';
        }else if( number > 4 && number <= 512 ){
            return '#fef1e1';
        }else if( number > 512){
            return '#faffff';
        }
    },
    colourBg1: function(number){
        switch( number ){
            case 2:return "#efe5db";
            case 4:return "#ecdfc8";
            case 8:return "#ff9661";
            case 16:return "#f39764";
            case 32:return "#f3804f";
            case 64:return "#fa5738";
            case 128:return "#f0cd77";
            case 256:return "#f0d062";
            case 512:return "#eec74e";
            case 1024:return "#e6b20d";
            case 2048:return "#c0950b";
            case 4096:return "#9b7e22";
            case 8192:return "#9b7e22";
            default:return "#a76909";
        }
    },
    //之前的
    colour2: function(number){
        if( number > 0 && number <= 8 ){
            return '#fa3232';
        }else if( number > 8 && number <= 64 ){
            return '#d57371';
        }else if( number > 64 && number <= 512 ){
            return '#fa9c38';
        }else if( number > 512 && number <= 4096 ){
            return '#8d5f9e';
        }else if( number >= 8192 ){
            return '#ffffff';
        }
    },
    colourBg2: function(number){
        switch( number ){
            case 2:return "#fdfbf3";break;
            case 4:return "#fff9e7";break;
            case 8:return "#ffffd9";break;
            case 16:return "#ffffcc";break;
            case 32:return "#ffffbf";break;
            case 64:return "#e6fabe";break;
            case 128:return "#ccffcc";break;
            case 256:return "#ccf7bc";break;
            case 512:return "#d9ffb2";break;
            case 1024:return "#c9e5ac";break;
            case 2048:return "#ace5ac";break;
            case 4096:return "#96e1bc";break;
            case 8192:return "#99cccc";break;
            case 16384:return "#739999";break;
        }
    },
    //皮肤3
    colour3: function(number){
        if( number > 0 && number <= 8 ){
            return '#f67c60';
        }else if( number > 8 && number <= 32 ){
            return '#a3af28';
        }
        else if( number >= 64 && number <= 512 ){
            return '#1d9fbe';
        }else if( number > 512 ){
            return '#faffff';
        }/*else if( number > 1024 ){
         return '#ffffff';
         }*/
    },
    colourBg3: function(number){
        switch( number ){
            case 2:return "#ffffcc";break;
            case 4:return "#ffff99";break;
            case 8:return "#ffcc99";break;
            case 16:return "#f3c190";break;
            case 32:return "#e8e874";break;
            case 64:return "#cccc33";break;
            case 128:return "#99cc00";break;
            case 256:return "#99cc99";break;
            case 512:return "#94c494";break;
            case 1024:return "#7eb57e";break;
            case 2048:return "#71aa85";break;
            case 4096:return "#71aa9d";break;
            case 8192:return "#5e9387";break;
            default:return "#283739";break;
        }
    },
    //皮肤4
    colour4: function(number){
        if( number > 0 && number < 8 ){
            return '#666666';
        }else if(number == 8){
            return '#ac9a76';
        }else if( number > 8 && number <= 32 ){
            return '#c39ea2';
        }else if( number >= 64 && number <= 512 ){
            return '#f27369';
        }else if( number >= 1024 ){
            return '#ffffff';
        }
    },
    colourBg4: function(number){
        var color;
        switch( number ){
            case 2:return "#e9e3d3";break;
            case 4:return "#f2e4c7";break;
            case 8:return "#ffffd9";break;
            case 16:return "#f5f5cf";break;
            case 32:return "#fae6bd";break;
            case 64:return "#f2d69d";break;
            case 128:return "#f4d088";break;
            case 256:return "#e1be77";break;
            case 512:return "#cca962";break;
            case 1024:return "#ba8941";break;
            case 2048:return "#a87d3e";break;
            case 4096:return "#946f38";break;
            case 8192:return "#755a32";break;
            default:return "#4c3a1f";break;
        }
        return color;
    },
    //大背景
    bgs: function(number){
        var color;
        switch (number){
            case 1:
                color = '#bcaea1';break;
            case 2:
                color = '#945921';break;
            case 3:
                color = '#98b6ce';break;
            case 4:
                color = '#bcaea1';break;
            default:
                color = '#bcaea1';break;
        }
        return color;
    },
    //格子背景色
    cellBg: function(number){
        var color;
        switch (number){
            case 1:
                color = '#ccc0b4';break;
            case 2:
                color = '#ccffff';break;
            case 3:
                color = '#78acd4';break;
            case 4:
                color = '#bca686';break;
            default:
                color = '#ccc0b4';break;
        }
        return color;
    }
}