<script setup lang="ts">
type PopupProps = {
  modelValue: boolean
  cross?: boolean
}

const props = withDefaults(defineProps<PopupProps>(), {
  cross: true,
})
defineEmits(['update:modelValue'])



</script>

<template>
  <div
      @click.self="$emit('update:modelValue', false)"
      :class="props.modelValue ? 'active' : ''"
      class="popup">
    <div class="popup-main">
      <slot></slot>
    </div>
  </div>
</template>

<style lang="scss">
.popup {
  display: flex;
  position: fixed;
  width: 100%;
  height: 100%;
  top: 0;
  left: 0;
  pointer-events: none;
  background: rgba(230, 235, 239, 0.4);
  transition: opacity 0.1s ease-in-out;
  z-index: 99;

  opacity: 0;

  &.active {
    pointer-events: auto;
    opacity: 1;
  }


  &-main {
    width: 973px;
    position: relative;
    border-radius: 12px;
    box-shadow: -20px -20px 40px 0px rgba(209, 217, 230, 1), 20px 20px 40px 0px rgba(209, 217, 230, 1);


    background-color: #E6EBEF;
    padding: 50px 50px;
    margin: auto;

    @media (max-width: 640px) {
      padding: 50px 20px;
    }


    &__title {
      text-align: center;
      color: #000;
      font-size: 32px;
      font-family: Montserrat;
      font-style: normal;
      font-weight: 500;
      line-height: normal;
    }

    &__sub_title {
      margin-top: 40px;
      text-align: left;
      color: #000;
      font-size: 20px;
      font-family: Montserrat;
      font-style: normal;
      font-weight: 500;
      line-height: normal;
    }
  }


  &.popup_contactus {

    .popup-main__title {
      color: #000;
      font-family: Montserrat;
      font-size: 32px;
      font-style: normal;
      font-weight: 500;
      line-height: normal;
      text-align: center;
    }

    .popup-main__sub_title {
      margin-top: 20px;
      color: #818181;
      font-family: Montserrat;
      font-size: 14px;
      font-style: normal;
      font-weight: 400;
      line-height: normal;
      text-align: center;
    }

    .popup-main__buttons {
      max-width: 321px;
      margin-top: 40px;
      margin-left: auto;
      margin-right: auto;
      display: flex;
      flex-direction: column;
      align-items: center;

      .soc_but {
        width: 100%;
        height: 41px;
        border-radius: 3px;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-bottom: 20px;
        cursor: pointer;
        text-decoration: none;
        position: relative;
        padding: 0 5px;

        &:hover {
          span {
            position: relative;
            z-index: 2;
          }

          &:after {
            content: "";
            position: absolute;
            top: 0;
            bottom: 0;
            left: 0;
            right: 0;
            background: rgba(0, 0, 0, 0.1);
            pointer-events: none;
            z-index: 1;
          }
        }


        &.mail {
          background: #4778F5;

          .icon::before {
            margin-left: -30px;
            width: 20px;
            height: 16px;
            background-image: url("/images/soc_light_contact_mail.svg");
          }
        }

        &.wa {
          background: #25D366;

          .icon::before {
            margin-left: -30px;
            width: 20px;
            height: 20px;
            background-image: url("/images/soc_light_contact_wa.svg");
          }
        }

        &.tg {
          background: #2AABEE;

          .icon::before {
            margin-left: -31px;
            width: 21px;
            height: 18px;
            background-image: url("/images/soc_light_contact_tg.svg");
          }
        }

        .icon {
          color: #FFF;
          /* Button_1 */
          font-family: Nunito;
          font-size: 14px;
          font-style: normal;
          font-weight: 600;
          line-height: normal;
          letter-spacing: 2.38px;
          text-transform: uppercase;
          padding-left: 30px;

          &::before {
            content: "";
            display: block;
            position: absolute;
            width: 100%;
            height: 100%;
            background-repeat: no-repeat;
          }


        }
      }
    }
  }

  &.popup_form_checkout {
    .field_email {
      margin-top: 25px;
      color: #000;
      font-size: 14px;
      font-family: Montserrat;
      font-style: normal;
      font-weight: 600;
      line-height: normal;
    }

    #field_email {
      margin-top: 10px;
      padding: 13px 17px;
      width: 100%;
      border: 1px solid rgba(0, 0, 0, 0.50);

      color: rgba(1, 1, 1, 0.8);
      background-color: transparent;

      &::placeholder {
        color: #C5C5C5;
      }
    }

    .booklist {
      margin-top: 25px;

      > div {
        color: #000;
        font-size: 18px;
        font-family: Montserrat;
        font-style: normal;
        font-weight: 600;
        line-height: normal;
      }

      .name {
        float: left;
      }

      .cost {
        float: right;
      }

      .clear {
        width: 100%;
        clear: both;
      }

      padding-bottom: 30px;
      border-bottom: 1px solid black;
    }

    .booktotal {
      margin-top: 40px;

      > div {
        color: #000;
        font-size: 26px;
        font-family: Montserrat;
        font-style: normal;
        font-weight: 700;
        line-height: normal;
      }

      .name {
        float: left;
      }

      .cost {
        float: right;
      }

      .clear {
        width: 100%;
        clear: both;
      }
    }

    .but_block {

      margin-top: 50px;
      text-align: center;

      .order_but {
        color: white;
        background-color: #FF844F;

        /* Button_1 */
        font-size: 14px;
        font-family: 'Nunito', sans-serif;
        font-weight: 600;
        letter-spacing: 2.38px;
        text-transform: uppercase;

        border-radius: 3px;

        padding: 11px 14px;

        cursor: pointer;
        @media (max-width: 404px) {
          width: 100%;
          text-align: center;
        }

        &:hover {
          color: #FF844F;
          background-color: white;
          outline: 2px solid #FF844F;
        }
      }
    }
  }
}
</style>