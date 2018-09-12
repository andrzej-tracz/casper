import React from 'react';
import PropTypes from 'prop-types';

class Table extends React.Component {

  static propTypes = {
    columns: PropTypes.array.isRequired,
    items: PropTypes.array.isRequired,
    options: PropTypes.func,
  };

  renderHead = () => {
    const cells = [];

    this.props.columns.forEach((column, key) => {
      cells.push(
        <th key={key} className={column.headerClassName}>
          {column.label}
        </th>
      );
    });

    return (
      <thead>
      <tr>
        {cells}
        {this.props.options && (
          <th/>
        )}
      </tr>
      </thead>
    );
  };

  renderRow = (item) => {
    const cells = [];

    this.props.columns.forEach((column, key) => {
      cells.push(
        <td key={key} className={column.className}>
          {this.renderCellValue(column, item)}
        </td>
      );
    });

    return cells;
  };

  renderCellValue = (column, item) => {
    return column.value ? column.value(item) : item[column.key];
  };

  renderBody = () => {
    return (
      <tbody>
      {this.props.items.map((item, key) => {
        return (
          <tr key={key}>
            {this.renderRow(item)}
            {this.props.options && (
              <td className={'options-wrapper'}>
                {this.props.options(item)}
              </td>
            )}
          </tr>
        );
      })}
      </tbody>
    );
  };

  render() {
    return (
      <div className=" mb-2 mt-2">
        <table className="table table-striped">
          {this.renderHead()}
          {this.renderBody()}
        </table>
      </div>
    );
  }
}

export { Table };
